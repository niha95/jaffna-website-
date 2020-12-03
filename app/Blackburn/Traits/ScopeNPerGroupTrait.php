<?php
namespace App\Blackburn\Traits;

trait ScopeNPerGroupTrait {

    public function scopeNPerGroup($query, $group, $n = 10)
    {
        $table = ($this->getTable());

        $query->from(\DB::raw("(SELECT @rank:=0, @group:=0) as vars, {$table}"));

        if (!$query->getQuery()->columns) {
            $query->select("{$table}.*");
        }

        $groupAlias = 'group_' . md5(time());
        $rankAlias = 'rank_' . md5(time());

        $query->addSelect(\DB::raw(
            "@rank := IF(@group = {$group}, @rank+1, 1) as {$rankAlias}, @group := {$group} as {$groupAlias}"
        ));

        $query->getQuery()->orders = (array)$query->getQuery()->orders;
        array_unshift($query->getQuery()->orders, ['column' => $group, 'direction' => 'asc']);

        $subQuery = $query->toSql();

        $newBase = $this->newQuery()
            ->from(\DB::raw("({$subQuery}) as {$table}"))
            ->mergeBindings($query->getQuery())
            ->where($rankAlias, '<=', $n)
            ->getQuery();

        $query->setQuery($newBase);
    }
}