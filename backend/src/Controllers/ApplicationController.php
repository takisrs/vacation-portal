<?php

namespace takisrs\Controllers;

use takisrs\Core\Controller;
use takisrs\Helpers\EmailTemplate;
use takisrs\Models\Application;
use takisrs\Models\User;

/**
 * ApplicationController
 */
class ApplicationController extends Controller
{

    /**
     * Retrieves and responses with a list of applications of the authorized user
     *
     * @return void
     */
    public function list(): void
    {
        // if the authenticated user is an admin, fetch all the applications else fetch only user's applications
        if ($this->request->user()->isAdmin()) {
            $applications = (new Application)->findAll();
        } else {
            $applications = $this->request->user()->applications();
        }

        // add days of vacation to each application object as it needed in the api response
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
     * Creates a new application and responses with the result of the operation
     *
     * @return void
     */
    public function create(): void
    {
        try {
            $application = new Application;
            $application->userId = $this->request->user()->id;
            $application->dateFrom = $this->request->body('dateFrom');
            $application->dateTo = $this->request->body('dateTo');
            $application->reason = $this->request->body('reason');
            $application->status = Application::STATUS_PENDING;
            $application->createdAt = (new \DateTime('now'))->format("Y-m-d H:i:s");

            $application = $application->create();

            if ($application) {

                // sends an email to all the admins, informing them about the new application
                $admins = (new User())->findBy(["type" => User::TYPE_ADMIN]);

                $email = new EmailTemplate(dirname(__DIR__) . '/EmailTemplates/submitted.tem.php');
                $email->replaceVars([
                    'user' => $this->request->user()->firstName . ' ' . $this->request->user()->lastName,
                    'vacation_start' => $application->dateFrom,
                    'vacation_end' => $application->dateTo,
                    'reason' => $application->reason,
                    'approve_link' => '<a href="http://localhost:8081/applications/' . $application->id . '/approve">Approve</a>',
                    'reject_link' => '<a href="http://localhost:8081/applications/' . $application->id . '/reject">Reject</a>'
                ]);

                foreach ($admins as $admin)
                    $email->send($admin->email);

                $this->response->status(200)->send([
                    "ok" => true,
                    "message" => "Application created successfully",
                    "data" => [
                        "application" => $application
                    ]
                ]);
            } else {
                throw new \Exception("Application not created");
            }
        } catch (\Exception $e) {
            $this->response->status(401)->send([
                "ok" => false,
                "message" => sprintf("Application's submission failed: %s", $e->getMessage())
            ]);
        }
    }

    /**
     * Approves an application
     * 
     * The method change the status of an application to rejected, and inform the user accordingly with an email.
     *
     * @return void
     */
    public function approve(): void
    {
        try {
            $application = new Application;
            $application = $application->find($this->request->param("id"));

            if (!isset($application)) throw new \Exception("Application not found");

            // change the status of the application only if is in pending status
            if ($application->status != Application::STATUS_PENDING)
                throw new \Exception(sprintf("Application %d has already been %s", $application->id, $application->status == Application::STATUS_APPROVED ? 'approved' : 'rejected'));

            $application->approve();

            // alert the user about the approval of his application
            $email = new EmailTemplate(dirname(__DIR__) . '/EmailTemplates/approved.tem.php');
            $email->replaceVar('submission_date', $application->createdAt);
            $email->send($application->user()->email);

            $this->response->status(200)->send([
                "ok" => true,
                "message" => "Application approved",
                "data" => [
                    "application" => $application
                ]
            ]);
        } catch (\Exception $e) {
            $this->response->status(401)->send([
                "ok" => true,
                "message" => sprintf("Application approval failed: %s", $e->getMessage())
            ]);
        }
    }

    /**
     * Rejects an application
     * 
     * The method change the status of an application to rejected, and inform the user accordingly with an email.
     *
     * @return void
     */
    public function reject(): void
    {
        try {
            $application = new Application;
            $application = $application->find($this->request->param("id"));

            if (!isset($application)) throw new \Exception("Application not found");

            // change the status of the application only if is in pending status
            if ($application->status != Application::STATUS_PENDING)
                throw new \Exception(sprintf("Application %d has already been %s", $application->id, $application->status == Application::STATUS_APPROVED ? 'approved' : 'rejected'));

            $application->reject();

            // alert the user with an email about the rejection of his application
            $email = new EmailTemplate(dirname(__DIR__) . '/EmailTemplates/rejected.tem.php');
            $email->replaceVar('submission_date', $application->createdAt);
            $email->send($application->user()->email);

            $this->response->status(200)->send([
                "ok" => true,
                "message" => sprintf("Application %d rejected", $application->id),
                "data" => [
                    "application" => $application
                ]
            ]);
        } catch (\Exception $e) {
            $this->response->status(401)->send([
                "ok" => true,
                "message" => sprintf("Application rejection failed: %s", $e->getMessage())
            ]);
        }
    }
}
