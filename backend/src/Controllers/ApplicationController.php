<?php

namespace takisrs\Controllers;

use takisrs\Core\Controller;
use takisrs\Helpers\EmailTemplate;
use takisrs\Models\Application;

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

        $application = new Application;
        if ($this->request->user()->isUser()) {
            $applications = $application->findBy([
                'userId' => $this->request->user()->id
            ]);
        } else {
            $applications = $application->findAll();
        }

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
                // send an email
                $email = new EmailTemplate(dirname(__DIR__) . '/EmailTemplates/submitted.tem.php');
                $email->replaceVars([
                    'user' => $this->request->user()->firstName . ' ' . $this->request->user()->lastName,
                    'vacation_start' => $application->dateFrom,
                    'vacation_end' => $application->dateTo,
                    'approve_link' => '<a href="#">Approve</a>',
                    'reject_link' => '<a href="#">Reject</a>'
                ]);
                $email->send('takispadaz@gmail.com');

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

            $application->approve();

            // send an email
            $email = new EmailTemplate(dirname(__DIR__) . '/EmailTemplates/approved.tem.php');
            $email->replaceVar('submission_date', $application->createdAt);
            // get the user object to retrieve the email
            //$email->send("");

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

            $application->reject();

            // send an email
            $email = new EmailTemplate(dirname(__DIR__) . '/EmailTemplates/rejected.tem.php');
            $email->replaceVar('submission_date', $application->createdAt);
            // get the user object to retrieve the email
            //$email->send("");

            $this->response->status(200)->send([
                "ok" => true,
                "message" => "Application rejected",
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
