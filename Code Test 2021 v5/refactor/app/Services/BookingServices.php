<?php

namespace DTApi\Services;

use DTApi\Repository\BookingRepository;

class BookingServices
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function getUsersJobsServices($user_id)
    {
        return $this->bookingRepository->getUsersJobs($user_id);

    }

    public function getAllServices($request)
    {
        return $this->bookingRepository->getUsersJobs($request);

    }

    public function getJobsServices($request)
    {
        return $this->bookingRepository->with('translatorJobRel.user')->find($id);
    }

    public function storeServices($authUser, $data)
    {
        return $this->bookingRepository->store($authUser, $data);
    }

    public function updateJobServices($id, $data, $cuser)
    {
        return $this->bookingRepository->updateJob($id, $data, $cuser);
    }

    public function storeJobEmailServices($data)
    {
        return $this->bookingRepository->storeJobEmail($data);
    }

    public function getUsersJobsHistoryServices($user_id, $request)
    {
        return $this->bookingRepository->getUsersJobsHistory($user_id, $request);
    }

    public function acceptJobServices($data, $user)
    {
        return $this->bookingRepository->acceptJob($data, $user);
    }

    public function acceptJobWithIdServices($data, $user)
    {
        return $this->bookingRepository->acceptJobWithId($data, $user);
    }

    public function cancelJobAjaxServices($data, $user)
    {
        return $this->bookingRepository->cancelJobAjax($data, $user);
    }

    public function endJobServices($data)
    {
        return $this->bookingRepository->endJob($data);
    }

    public function customerNotCallServices($data)
    {
        return $this->bookingRepository->customerNotCall($data);
    }

    public function getPotentialJobsServices($user)
    {
        return $this->bookingRepository->getPotentialJobs($user);
    }

    public function reopenServices($user)
    {
        return $this->bookingRepository->reopen($user);
    }

    public function resendSMSNotifications($jobId)
    {

        $job = $this->bookingRepository->find($jobId);
        $job_data = $this->bookingRepository->jobToData($job);

        try {
            $this->bookingRepository->sendSMSNotificationToTranslator($job);
            return ['success' => 'SMS sent'];
        } catch (\Exception $e) {
            return ['err' => $e->getMessage()];
        }
    }

    public function resendNotifications($jobId)
    {
        $job = $this->bookingRepository->find($jobId);
        $job_data = $this->bookingRepository->jobToData($job);
        $this->bookingRepository->sendNotificationTranslator($job, $job_data, '*');
    }

    public function distanceServices($jobid, $distance, $time)
    {
        return $this->bookingRepository->distance($jobid, $distance, $time);
    }

    public function jobServices(...$data)
    {
        return $this->bookingRepository->distance($data);
    }

}