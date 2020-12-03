<?php
namespace App\Blackburn\Traits;

use Illuminate\Support\Str;

trait ReorderingTrait {

    public function moveUp()
    {
        return $this->reorder('up');

    }

    public function moveDown()
    {
        return $this->reorder('down');
    }

    public function reorder($direction)
    {
        $orderColumn = $this->getOrderColumn();

        $newOrder = $direction == 'up' ? $this->{$orderColumn} - 1 : $this->{$orderColumn} + 1;

        if ($direction == 'up') {
            if ($newOrder == 0) return false;
        } else {
            $builder = static::query();
            if($this->hasParentIdColumn()) $builder->where('parent_id', $this->parent_id);
            $itemsCount = $builder->count();

            if ($newOrder > $itemsCount) return false;
        }

        try {
            \DB::transaction(function () use ($newOrder, $orderColumn) {
                $builder = static::query();
                $builder->where($orderColumn, $newOrder);

                if($this->hasParentIdColumn()) $builder->where('parent_id', $this->parent_id);

                $builder->update([$orderColumn => $this->{$orderColumn}]);

                $this->{$orderColumn} = $newOrder;
                $this->save();
            });
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    protected function getOrderColumn()
    {
        if (isset($this->orderColumn)) return $this->orderColumn;

        return 'order';
    }

    protected function getRouteNameSegment()
    {
        if (isset($this->routeNameSegment)) return $this->routeNameSegment;

        if (isset($this->table)) return $this->table;

        return str_replace('\\', '', Str::snake(Str::plural(class_basename($this))));
    }

    protected function hasParentIdColumn()
    {
        if(defined('static::HAS_PARENT_ID_COLUMN')) return static::HAS_PARENT_ID_COLUMN;

        if(property_exists($this, 'hasParentIdColumn')) return $this->hasParentIdColumn;

        if(in_array('parent_id', $this->fillable)) return true;

        return false;
    }

    public function reorderButtons($count)
    {
        $routeSegment = $this->getRouteNameSegment();
        $orderColumn = $this->getOrderColumn();

        if ($this->{$orderColumn} == 1 && $count > 1) {
            return view('control_panel.common._reorder_buttons', [
                'moveDownUrl' => route("control_panel.{$routeSegment}.move_down", $this->id)
            ])->render();
        }

        if ($this->{$orderColumn} > 1 && $this->{$orderColumn} != $count) {
            return view('control_panel.common._reorder_buttons', [
                'moveUpUrl' => route("control_panel.{$routeSegment}.move_up", $this->id),
                'moveDownUrl' => route("control_panel.{$routeSegment}.move_down", $this->id)
            ])->render();
        }

        if ($this->{$orderColumn} == $count && $count != 1) {
            return view('control_panel.common._reorder_buttons', [
                'moveUpUrl' => route("control_panel.{$routeSegment}.move_up", $this->id)
            ])->render();
        }

        return null;
    }

    public static function syncOrder($model)
    {
        $orderColumn = $model->getOrderColumn();

        $builder = static::query();
        if($model->hasParentIdColumn()) $builder->where('parent_id', $model->parent_id);
        $itemsToSync = $builder->orderBy($orderColumn, 'asc')->get(['id', $orderColumn]);

        if($itemsToSync->isEmpty()) return;

        $statement = 'UPDATE ' . $model->getTable() . ' SET `' . $orderColumn . '` = (CASE ';

        $x = 1;
        foreach ($itemsToSync as $item) {
            $statement .= "WHEN `{$orderColumn}` = {$item->$orderColumn} THEN {$x} ";
            $x++;
        }

        $statement .= 'ELSE `' . $orderColumn .'` END) ';

        $whereClause = implode(', ', $itemsToSync->lists('id')->toArray());

        $statement .= "WHERE id IN ({$whereClause})";

        \DB::statement($statement);
    }

    public static function bootReorderingTrait()
    {
        static::deleted(function ($model) {
            if(method_exists($model, 'subMenus')) $model->subMenus()->delete();

            static::syncOrder($model);
        });

        static::creating(function ($model) {
            $builder = static::query();
            if($model->hasParentIdColumn()) $builder->where('parent_id', $model->parent_id);
            $count = $builder->count();

            $model->order = $count + 1;
        });

        static::updating(function ($model) {
            if(!$model->hasParentIdColumn()) return;

            if ($model->isDirty('parent_id')) {
                $parentLastItem = static::where('parent_id', $model->parent_id)
                    ->orderBy($model->getOrderColumn(), 'desc')
                    ->first();

                $model->order = $parentLastItem ? $parentLastItem->order + 1 : 1;
            }
        });
    }

}