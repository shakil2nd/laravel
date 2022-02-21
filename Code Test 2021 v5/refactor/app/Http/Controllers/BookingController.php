<?php

namespace DTApi\Http\Controllers;

use DTApi\Services\BookingServices;
use Illuminate\Http\Request;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{

    /**
     * @var BookingServices
     */
    protected $bookingServices;

    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingServices $bookingServices)
    {
        $this->bookingServices = $bookingServices;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        if ($user_id = $request->get('user_id')) {

            $response = $this->bookingServices->getUsersJobsServices($user_id);

        } elseif ($request->__authenticatedUser->user_type == env('ADMIN_ROLE_ID') || $request->__authenticatedUser->user_type == env('SUPERADMIN_ROLE_ID')) {
            $response = $this->bookingServices->getAllServices($request);
        }

        // There should be some error Handling I leave it for now.

        return response()->json($response, 201);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $job = $this->bookingServices->getJobsServices($id);

        return response()->json($job, 201);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $response = $this->bookingServices->storeServices($request->__authenticatedUser, $data);

        return response()->json($response, 201);

    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        $data = $request->all();
        $cuser = $request->__authenticatedUser;
        $response = $this->bookingServices->updateJobServices($id, array_except($data, ['_token', 'submit']), $cuser);

        return response()->json($response, 201);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {
        $adminSenderEmail = config('app.adminemail');
        $data = $request->all();

        $response = $this->bookingServices->storeJobEmailServices($data);

        return response()->json($response, 201);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        if ($user_id = $request->get('user_id')) {

            $response = $this->bookingServices->getUsersJobsHistoryServices($user_id, $request);
            return response()->json($response, 201);
        }

        return null;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->bookingServices->acceptJobServices($data, $user);

        return response()->json($response, 201);
    }

    public function acceptJobWithIdServices(Request $request)
    {
        $data = $request->get('job_id');
        $user = $request->__authenticatedUser;

        $response = $this->bookingServices->acceptJobWithIdServices($data, $user);

        return response()->json($response, 201);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->bookingServices->cancelJobAjaxServices($data, $user);

        return response()->json($response, 201);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {
        $data = $request->all();

        $response = $this->bookingServices->endJobServices($data);

        return response()->json($response, 201);

    }

    public function customerNotCall(Request $request)
    {
        $data = $request->all();

        $response = $this->bookingServices->customerNotCallServices($data);

        return response()->json($response, 201);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {
        $data = $request->all();
        $user = $request->__authenticatedUser;

        $response = $this->bookingServices->getPotentialJobsServices($user);

        return response()->json($response, 201);
    }

    public function distanceFeed(Request $request)
    {
        $data = $request->all();

        if (isset($data['distance']) && $data['distance'] != "") {
            $distance = $data['distance'];
        } else {
            $distance = "";
        }
        if (isset($data['time']) && $data['time'] != "") {
            $time = $data['time'];
        } else {
            $time = "";
        }
        if (isset($data['jobid']) && $data['jobid'] != "") {
            $jobid = $data['jobid'];
        }

        if (isset($data['session_time']) && $data['session_time'] != "") {
            $session = $data['session_time'];
        } else {
            $session = "";
        }

        if ($data['flagged'] == 'true') {
            if ($data['admincomment'] == '') {
                return "Please, add comment";
            }

            $flagged = 'yes';
        } else {
            $flagged = 'no';
        }

        if ($data['manually_handled'] == 'true') {
            $manually_handled = 'yes';
        } else {
            $manually_handled = 'no';
        }

        if ($data['by_admin'] == 'true') {
            $by_admin = 'yes';
        } else {
            $by_admin = 'no';
        }

        if (isset($data['admincomment']) && $data['admincomment'] != "") {
            $admincomment = $data['admincomment'];
        } else {
            $admincomment = "";
        }
        if ($time || $distance) {

            $affectedRows = $this->bookingServices->distanceServices($jobid, $distance, $time);
        }

        if ($admincomment || $session || $flagged || $manually_handled || $by_admin) {

            $affectedRows1 = $this->bookingServices->jobServices($jobid, $admincomment, $flagged, $manually_handled, $by_admin);

        }

        return response()->json(["msg" => "Record updated!"], 201);
    }

    public function reopen(Request $request)
    {
        $data = $request->all();
        $response = $this->bookingServices->reopen($data);

        return response()->json($response, 201);
    }

    public function resendNotifications(Request $request)
    {
        $data = $request->all();
        $this->bookingServices->resendNotifications($data['jobId']);

        return response()->json(['success' => 'Push sent']);
    }

    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        $data = $request->all();
        $response = $this->repository->resendSMSNotifications($data['jobid']);
        return response()->json($respons);
    }

}