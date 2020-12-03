<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Visit;
use Illuminate\Http\Request;

class ReportsController extends Controller {

    public function showLogin()
    {
        if (auth()->check()) {
            if (auth()->user()->canReport()) {
                return redirect()->route('site.reports.index');
            } else {
                auth()->logout();
            }
        }

        return view('site.reports.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password'),
            'status' => 'active',
        ];

        $rememberMe = $request->has('remember_me');

        if (auth()->attempt($credentials, $rememberMe)) {
            if (!auth()->user()->canReport()) {
                auth()->logout();

                flashMessage(trans('messages.email_and_password_do_not_match'), 'danger');
                return redirect()->back();
            }

            return redirect()->route('site.reports.index');
        }

        flashMessage(trans('messages.email_and_password_do_not_match'), 'danger');
        return redirect()->back();
    }

    public function logout()
    {
        auth()->logout();

        return redirect()->route('site.home');
    }

    public function index()
    {
        $reports = Report::with('visitsCountRelation')->latest()->where('user_id', auth()->id())->paginate(12);

        return view('site.reports.index', ['reports' => $reports]);
    }

    public function show($id)
    {
        $report = Report::findOrFail($id);

        if ($report->user->role == 'marketing_representative') {
            $view = 'site.reports.visits_tables._marketing_representative';
        } elseif ($report->user->role == 'marketing_supervisor') {
            $view = 'site.reports.visits_tables._marketing_supervisor';
        } elseif ($report->user->role == 'sales_representative') {
            $view = 'site.reports.visits_tables._sales_representative';
        } elseif ($report->user->role == 'sales_supervisor') {
            $view = 'site.reports.visits_tables._sales_supervisor';
        } else {
            $view = 'site.reports.visits_tables._distribution_representative';
        }

        return view('site.reports.show', [
            'report' => $report,
            'view_file' => $view
        ]);
    }

    public function create()
    {
        return view('site.reports.create');
    }

    public function store(Request $request)
    {
        $input = $request->only('area', 'period');
        $input['user_id'] = auth()->id();
        $input['status'] = 'new';

        $report = new Report($input);
        if ($report->save()) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.report')]));

            return $request->exists('save_and_new')
                ? redirect()->route('site.reports.create')
                : redirect()->route('site.reports.index');
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    public function createVisit($reportId)
    {
        $report = Report::findOrFail($reportId);

        if ($report->user->role == 'marketing_representative') {
            $view = 'site.reports.visits._marketing_representative';
        } elseif ($report->user->role == 'marketing_supervisor') {
            $view = 'site.reports.visits._marketing_supervisor';
        } elseif ($report->user->role == 'sales_representative') {
            $view = 'site.reports.visits._sales_representative';
        } elseif ($report->user->role == 'sales_supervisor') {
            $view = 'site.reports.visits._sales_supervisor';
        } else {
            $view = 'site.reports.visits._distribution_representative';
        }

        return view('site.reports.visits.create', [
            'report' => $report,
            'view_file' => $view,
        ]);
    }

    public function storeVisit($reportId, Request $request)
    {
        $report = Report::findOrFail($reportId);

        if ($report->user->role == 'marketing_representative') {
            return $this->storeMarketingRepresentativeVisit($report, $request);
        } elseif ($report->user->role == 'marketing_supervisor') {
            return $this->storeMarketingSupervisorVisit($report, $request);
        } elseif ($report->user->role == 'sales_representative') {
            return $this->storeSalesRepresentativeVisit($report, $request);
        } elseif ($report->user->role == 'sales_supervisor') {
            return $this->storeSalesSupervisorVisit($report, $request);
        } else {
            return $this->storeDistributionRepresentativeVisit($report, $request);
        }
    }

    protected function storeMarketingRepresentativeVisit($report, Request $request)
    {
        $input = $request->only('doctor_name', 'doctor_field', 'visit_time', 'doctor_classification',
            'product_name', 'pharmacy_name', 'doctor_comment', 'product_balance', 'comments');
        if ($report->period == 'am') $input['hospital_name'] = $request->get('hospital_name');

        $visit = new Visit(['details' => $input]);

        if ($report->visits()->save($visit)) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.visit')]));

            return $request->exists('save_and_new')
                ? redirect()->route('site.reports.create_visit', [$report->id])
                : redirect()->route('site.reports.index');
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    protected function storeMarketingSupervisorVisit($report, Request $request)
    {
        $input = $request->only('representative_name', 'doctor_name', 'doctor_field', 'visit_time', 'doctor_classification',
            'product_name', 'pharmacy_name', 'doctor_comment', 'product_balance', 'comments', 'supervisor_comments');

        $visit = new Visit(['details' => $input]);

        if ($report->visits()->save($visit)) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.visit')]));

            return $request->exists('save_and_new')
                ? redirect()->route('site.reports.create_visit', [$report->id])
                : redirect()->route('site.reports.index');
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    protected function storeSalesRepresentativeVisit($report, Request $request)
    {
        $input = $request->only('pharmacy_name', 'nearest_doctor', 'visit_time', 'visit_type',
            'pharmacy_classification', 'pharmacist_comment', 'product_balance', 'comments');

        $visit = new Visit(['details' => $input]);

        if ($report->visits()->save($visit)) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.visit')]));

            return $request->exists('save_and_new')
                ? redirect()->route('site.reports.create_visit', [$report->id])
                : redirect()->route('site.reports.index');
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    protected function storeSalesSupervisorVisit($report, Request $request)
    {
        $input = $request->only('representative_name', 'pharmacy_name', 'nearest_doctor', 'visit_time',
            'pharmacy_classification', 'pharmacist_comment', 'product_balance', 'comments');

        $visit = new Visit(['details' => $input]);

        if ($report->visits()->save($visit)) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.visit')]));

            return $request->exists('save_and_new')
                ? redirect()->route('site.reports.create_visit', [$report->id])
                : redirect()->route('site.reports.index');
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    protected function storeDistributionRepresentativeVisit($report, Request $request)
    {
        $input = $request->only('pharmacy_name', 'visit_time', 'visit_type',
            'rejection_cause', 'comments');

        $visit = new Visit(['details' => $input]);

        if ($report->visits()->save($visit)) {
            flashMessage(trans('messages.entity_created', ['entity' => trans('labels.visit')]));

            return $request->exists('save_and_new')
                ? redirect()->route('site.reports.create_visit', [$report->id])
                : redirect()->route('site.reports.index');
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }

    public function showChangePassword()
    {
        return view('site.reports.change_password');
    }

    public function doChangePassword(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed'
        ]);

        $user = auth()->user();

        $user->password = $request->get('password');

        if ($user->save()) {
            flashMessage(trans('messages.request_succeed'));

            return redirect()->route('site.reports.index');
        }

        flashMessage(trans('messages.request_failed'), 'danger');
        return redirect()->back();
    }
}