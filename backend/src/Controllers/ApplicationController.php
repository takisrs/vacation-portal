<?php

namespace takisrs\Controllers;

use takisrs\Core\Controller;
use takisrs\Core\HttpException;
use takisrs\Helpers\EmailTemplate;
use takisrs\Models\Application;
use takisrs\Models\User;

/**
 * Application Controller
 */
class ApplicationController extends Controller
{

    /**
     * Retrieves and returns the list of applications of the authorized user
     *
     * @return void
     */
    public function index(): void
    {
        // if the authenticated user is an admin, fetch all the applications else fetch only user's applications
        if ($this->request->user()->isAdmin()) {
            $applications = (new Application)->findAll('createdAt DESC');
        } else {
            $applications = $this->request->user()->applications();
        }

        if (!$applications) throw new HttpException(200, "No applications Found");

        // add days of vacation to each application object as we need them in the api response
        foreach ($applications as $index => $application)
            $applications[$index]->days = $application->days();

        $this->response->status(200)->send([
            "ok" => true,
            "message" => sprintf("Retrieved %d applications", count($applications)),
            "data" => [
                "applications" => $applications
            ]
        ]);
    }

    /**
     * Creates a new application and returns the result of the operation
     *
     * @return void
     */
    public function create(): void
    {
        // validate the request, to make sure that you have valid data
        $this->request->validate([
            'body.dateFrom' => ['required', 'date'],
            'body.dateTo' => ['required', 'date'],
            'body.reason' => ['required']
        ]);

        $application = new Application;
        $application->userId = $this->request->user()->id;
        $application->dateFrom = $this->request->body('dateFrom');
        $application->dateTo = $this->request->body('dateTo');
        $application->reason = $this->request->body('reason');
        $application->status = Application::STATUS_PENDING;
        $application->createdAt = (new \DateTime('now'))->format("Y-m-d H:i:s");

        $application = $application->create();

        if (!$application) throw new HttpException(401, "Application not created");

        // sends an email to all the admins, informing them about the new application
        $admins = (new User())->findBy(["type" => User::TYPE_ADMIN]);

        $email = new EmailTemplate(dirname(__DIR__) . '/EmailTemplates/submitted.tem.php');
        $email->replaceVars([
            'user' => $this->request->user()->firstName . ' ' . $this->request->user()->lastName,
            'vacation_start' => $application->dateFrom,
            'vacation_end' => $application->dateTo,
            'reason' => $application->reason,
            'approve_link' => '<a href="' . $_ENV['FRONTEND_URL'] . '/applications/' . $application->id . '/approve">Approve</a>',
            'reject_link' => '<a href="' . $_ENV['FRONTEND_URL'] . '/applications/' . $application->id . '/reject">Reject</a>'
        ]);

        foreach ($admins as $admin)
            $email->send($admin->email);

        $this->response->status(201)->send([
            "ok" => true,
            "message" => "Application created successfully",
            "data" => [
                "application" => $application
            ]
        ]);
    }

    /**
     * Approves an application
     * 
     * The method changes the status of an application to "approved", and informs the user with an email.
     *
     * @return void
     */
    public function approve(): void
    {

        $application = new Application;
        $application = $application->find($this->request->param("id"));

        if (!isset($application)) throw new HttpException(404, "Application not found");

        // change the status of the application only if is in pending status
        if ($application->status != Application::STATUS_PENDING)
            throw new HttpException(403, sprintf("Application %d has already been %s", $application->id, $application->status == Application::STATUS_APPROVED ? 'approved' : 'rejected'));

        $application->approve();

        // alert the user about the approval of his application
        $email = new EmailTemplate(dirname(__DIR__) . '/EmailTemplates/approved.tem.php');
        $email->replaceVar('submission_date', $application->createdAt)->send($application->user()->email);

        $this->response->status(200)->send([
            "ok" => true,
            "message" => "Application approved",
            "data" => [
                "application" => $application
            ]
        ]);
    }

    /**
     * Rejects an application
     * 
     * The method changes the status of an application to "rejected", and inform the user with an email.
     *
     * @return void
     */
    public function reject(): void
    {
        $application = new Application;
        $application = $application->find($this->request->param("id"));

        if (!isset($application)) throw new HttpException(404, "Application not found");

        // change the status of the application only if is in pending status
        if ($application->status != Application::STATUS_PENDING)
            throw new HttpException(403, sprintf("Application %d has already been %s", $application->id, $application->status == Application::STATUS_APPROVED ? 'approved' : 'rejected'));

        $application->reject();

        // alert the user with an email about the rejection of his application
        $email = new EmailTemplate(dirname(__DIR__) . '/EmailTemplates/rejected.tem.php');
        $email->replaceVar('submission_date', $application->createdAt)->send($application->user()->email);

        $this->response->status(200)->send([
            "ok" => true,
            "message" => sprintf("Application %d rejected", $application->id),
            "data" => [
                "application" => $application
            ]
        ]);
    }
}
