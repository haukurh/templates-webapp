<?php

namespace Template\App\Controller;

use Exception;
use JsonException;
use Template\App\Data;
use Template\Core\App;

class TemplateController
{

    /**
     * @throws Exception
     */
    public function create()
    {
        $csrfToken = $this->csrfToken();

        return view('template', [
            'id' => uuid4(),
            'csrfToken' => $csrfToken,
        ]);
    }

    /**
     * @throws Exception
     */
    public function edit(string $id)
    {
        $csrfToken = $this->csrfToken();
        /** @var Data $data */
        $data = App::get('data');

        $entry = $data->getEntryById($id);
        if (!$entry) {
            throw new Exception("Unable to find template with id: {$id}");
        }
        $entry['csrfToken'] = $csrfToken;

        return view('template', $entry);
    }

    public function delete(string $id)
    {
        try {
            $this->validateCsrfToken();
            /** @var Data $data */
            $data = App::get('data');

            $data->remove($id);

            json([
                'message' => 'Template deleted',
            ]);
        } catch (Exception $exception) {
            json([
                'message' => $exception->getMessage(),
            ], 400);
        }
    }

    protected function csrfToken(): string
    {
        session_start();

        $csrfToken = md5(random_bytes(32));
        $_SESSION['csrf-token'] = $csrfToken;
        return $csrfToken;
    }

    /**
     * @throws Exception
     */
    protected function validateCsrfToken(): void
    {
        session_start();
        $headers = apache_request_headers();

        if (!isset($_POST['csrf-token']) && !isset($headers['X-csrf-token'])) {
            throw new Exception('CSRF token missing');
        }

        $csrfToken = $headers['X-csrf-token'] ?: $_POST['csrf-token'];

        if ($_SESSION['csrf-token'] !== $csrfToken) {
            throw new Exception('Invalid CSRF token');
        }
    }

    /**
     * @throws JsonException
     */
    public function save()
    {
        $this->validateCsrfToken();

        /** @var Data $data */
        $data = App::get('data');

        $payload = array_intersect_key($_POST, [
            'id' => null,
            'name' => null,
            'body' => null,
        ]);

        $uuidRegex = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
        if (!preg_match($uuidRegex, $payload['id'])) {
            throw new Exception('Invalid uuid');
        }

        if (empty($payload['name'])) {
            throw new Exception('Name cannot be empty!');
        }

        if (empty($payload['body'])) {
            throw new Exception('Body cannot be empty!');
        }

        $data->add($payload);

        redirect('');
    }

}
