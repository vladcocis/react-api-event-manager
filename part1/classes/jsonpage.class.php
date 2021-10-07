<?php
/**
 * Creates a JSON page based on the parameters
 *
 * @author Vlad Cocis
 *
 */
class JSONpage
{
    private $page;
    private $recordset;

    /**
     * Constructor
     *
     * @param $pathArr - an array containing the route information
     * @param $recordset - JSON recordset
     *
     */
    public function __construct($pathArr, $recordset)
    {
        $this->recordset = $recordset;
        $path = (empty($pathArr[1])) ? "api" : $pathArr[1];

        switch ($path) {
            case 'api':
                $this->page = $this->json_welcome();
                break;
            case 'authors':
                $this->page = $this->json_authors();
                break;
            case 'login':
                $this->page = $this->json_login();
                break;
            case 'days':
                $this->page = $this->json_days();
                break;
            case 'day-details':
                $this->page = $this->json_day_details();
                break;
            case 'slot-details':
                $this->page = $this->json_slot_details();
                break;
            case 'sessions':
                $this->page = $this->json_sessions();
                break;
            case 'session-content':
                $this->page = $this->json_session_content();
                break;
            case 'presentations':
                $this->page = $this->json_presentations();
                break;
            case 'update':
                $this->page = $this->json_update();
                break;
            default:
                $this->page = $this->json_error();
                break;
        }
    }

    /**
     * Removes special characters from string, in order to sanitise
     * an arbitrary max length of 20 is set
     *
     * @param $x - the string to be sanitised
     * 
     * @return - the sanitised string
     *
     */
    private function sanitiseString($x)
    {
        return substr(trim(filter_var($x, FILTER_SANITIZE_STRING)), 0, 20);
    }

    /**
     * Validates int and trims length
     * an arbitrary max range of 100000 is set
     *
     * @param $x - the int to be validated and trimmed
     *
     * @return - the processed int
     * 
     */
    private function sanitiseNum($x)
    {
        return filter_var($x, FILTER_VALIDATE_INT, array("options" => array("min_range" => 0, "max_range" => 100000)));
    }

    /**
     * Welcome Endpoint
     *
     * Provides a greeting message to the user as well as information about the api and endpoints in JSON form
     *
     * @return - the encoded JSON
     * 
     */
    private function json_welcome()
    {
        $endpoints = array("endpoint 1" => "api", "endpoint 2" => "authors", "endpoint 3" => "login", "endpoint 4" => "days", "endpoint 5" => "day-details", "endpoint 6" => "slot-details", "endpoint 7" => "sessions", "endpoint 8" => "session-content", "endpoint 9" => "presentations", "endpoint 10" => "update");
        $msg = array("message" => "welcome", "author" => "Vlad Cocis", "endpoints" => $endpoints);
        return json_encode($msg);
    }

    /**
     * Error Endpoint
     *
     * Provides an error message in case of wrong endpoint in URL in JSON form
     * 
     * @return - the encoded JSON
     *
     */
    private function json_error()
    {
        $msg = array("message" => "Endpoint invalid");
        return json_encode($msg);
    }

    /**
     * Authors Endpoint
     *
     * Returns a list of authors searchable by name and ID in JSON form
     *
     * @param id - id of author for search
     * @param search - name of author for search
     * Parameters are passed in the URL : /api/authors?id=8192 or /api/authors?search=jennifer
     * 
     * @return - the encoded JSON
     *
     */
    private function json_authors()
    {
        $query = "SELECT name, authorId FROM authors";
        $params = [];

        if (isset($_REQUEST['search'])) {
            $query .= " WHERE name LIKE :term";
            $term = $this->sanitiseString("%" . $_REQUEST['search'] . "%");
            $params = ["term" => $term];
        } else {
            if (isset($_REQUEST['id'])) {
                $query .= " WHERE authorId = :term";
                $term = $this->sanitiseNum($_REQUEST['id']);
                $params = ["term" => $term];
            }
        }

        if (isset($_REQUEST['page'])) {
            $query .= " ORDER BY name";
            $query .= " LIMIT 10 ";
            $query .= " OFFSET ";
            $query .= 10 * ($this->sanitiseNum($_REQUEST['page']) - 1);
        }

        return ($this->recordset->getJSONRecordSet($query, $params));
    }

    /**
     * Login Endpoint
     *
     * Verifies user credentials and allows access to restricted parts of application
     *
     * @param email - email of user
     * @param password - password of user
     * Parameters are not passed in the URL like other endpoints, but as encoded JSON
     * 
     * @return - the encoded JSON
     *
     */
    private function json_login()
    {
        $msg = "Invalid request. Username and password required";
        $status = 400;
        $token = null;
        $input = json_decode(file_get_contents("php://input"));

        if (!is_null($input->email) && !is_null($input->password)) {
            $query = "SELECT username, admin, password FROM users WHERE email LIKE :email";
            $params = ["email" => $input->email];
            $res = json_decode($this->recordset->getJSONRecordSet($query, $params), true);

            if (password_verify($input->password, $res['data'][0]['password'])) {
                $msg = "User authorised. Welcome " . $res['data'][0]['username'];
                $status = 200;
                $token = array();
                $token['email'] = $input->email;
                $token['username'] = $res['data'][0]['username'];
                $token['admin'] = $res['data'][0]['admin'];
                $token['iat'] = time();
                $token['exp'] = time() + (60 * 60);
                $token = \Firebase\JWT\JWT::encode($token, JWTKEY);
            } else {
                $msg = "username or password are invalid";
                $status = 401;
            }
        }

        return json_encode(array("status" => $status, "message" => $msg, "token" => $token));
    }

    /**
     * Days Endpoint
     *
     * Provides a list of conference days from the database in JSON form
     * 
     * @return - the encoded JSON
     *
     */
    private function json_days()
    {
        $query = "SELECT DISTINCT dayInt, dayString FROM slots WHERE dayInt>=1 AND dayInt<5";
        $params = [];

        return ($this->recordset->getJSONRecordSet($query, $params));
    }

    /**
     * Day Details Endpoint
     *
     * Provides a list of all conference sessions and breaks in a given day in JSON form
     *
     * @param day - number of day, int
     * Parameter is passed in the URL : /api/day-details?day=1
     * 
     * @return - the encoded JSON
     *
     */
    private function json_day_details()
    {
        if ($_REQUEST['day']) {
            $day = $_REQUEST['day'];
            $query = "SELECT DISTINCT slotId, type, startHour, startMinute, endHour, endMinute FROM slots WHERE dayInt='$day'";
            $params = [];
            return ($this->recordset->getJSONRecordSet($query, $params));
        } else {
            return json_encode(['status' => 503, 'message' => 'Missing parameter: day ']);
        }

    }

    /**
     * Slot Details Endpoint
     *
     * Provides details of all conference sessions and breaks in a given time slot in JSON form
     *
     * @param slotId - database id of slot
     * Parameter is passed in the URL : /api/slot-details?slotId=10209
     * 
     * @return - the encoded JSON
     *
     */
    private function json_slot_details()
    {
        if ($_REQUEST['slotId']) {
            $slotId = $_REQUEST['slotId'];
            $query = "SELECT sessionId, sessions.name, session_types.name as 'type', rooms.name as 'room', authors.name as 'chair' FROM sessions
            JOIN session_types ON session_types.typeId = sessions.typeId
            JOIN rooms ON rooms.roomId = sessions.roomId
            LEFT OUTER JOIN authors ON authors.authorId = sessions.chairId
            WHERE slotId='$slotId'";
            $params = [];
            return ($this->recordset->getJSONRecordSet($query, $params));
        } else {
            return json_encode(['status' => 503, 'message' => 'Missing parameter: slotId.']);
        }

    }

    /**
     * Sessions Endpoint
     *
     * Provides a list of all sessions during the conference with details such as name, type, room, author and day in JSON form
     * 
     * @return - the encoded JSON
     * 
     */
    private function json_sessions()
    {
        $query = "SELECT sessions.sessionId, sessions.name as \"session_name\", session_types.name as \"type\", rooms.name as \"room\", authors.name as \"author\", slots.dayString from sessions left join session_types on sessions.typeId = session_types.typeId left join rooms on sessions.roomId = rooms.roomId left join authors on sessions.chairId = authors.authorId left join slots on sessions.slotId = slots.slotId";
        $params = [];

        return ($this->recordset->getJSONRecordSet($query, $params));
    }

    /**
     * Session Content Endpoint
     *
     * Provides list of all presentations and their associated awards, discussed in a given session in JSON form
     *
     * @param sessionId - database id of session
     * Parameter is passed in the URL : /api/session-details?sessionId=2375
     * 
     * @return - the encoded JSON
     *
     */
    private function json_session_content()
    {
        if ($_REQUEST['sessionId']) {
            $sessionId = $_REQUEST['sessionId'];

            $query = "SELECT title, abstract, award FROM content
                JOIN sessions_content ON sessions_content.contentId=content.contentId
                WHERE sessions_content.sessionId='$sessionId'";
            $params = [];
            return ($this->recordset->getJSONRecordSet($query, $params));
        } else {
            return json_encode(['status' => 503, 'message' => 'Missing parameter: sessionId.']);
        }
    }

    /**
     * Presentations Endpoint
     *
     * Returns a list list of all presentations, searchable by title and author in JSON form
     *
     * @param id - id of author for search
     * @param search - name of author for search
     * Parameters are passed in the URL : /api/presentations?id=8192 or /api/presentations?search=gender
     * 
     * @return - the encoded JSON
     * 
     */
    private function json_presentations()
    {
        $query = "SELECT content.title, authors.name FROM content_authors join content on content_authors.contentId = content.contentId join authors on content_authors.authorId = authors.authorId";
        $params = [];

        if (isset($_REQUEST['search'])) {
            $query .= " WHERE content.title LIKE :term";
            $term = $this->sanitiseString("%" . $_REQUEST['search'] . "%");
            $params = ["term" => $term];
        } else {
            if (isset($_REQUEST['id'])) {
                $query .= " WHERE content_authors.authorId = :term";
                $term = $this->sanitiseNum($_REQUEST['id']);
                $params = ["term" => $term];
            }
        }

        if (isset($_REQUEST['page'])) {
            $query .= " ORDER BY name";
            $query .= " LIMIT 10 ";
            $query .= " OFFSET ";
            $query .= 10 * ($this->sanitiseNum($_REQUEST['page']) - 1);
        }

        return ($this->recordset->getJSONRecordSet($query, $params));
    }

    /**
     * Update Endpoint
     *
     * Allows administrators to update the title of a given session
     *
     * @param token - stored in localStorage when logging in
     * @param sessionId - database ID of session to be modified
     * @param name - updated name of session
     * Parameters are not passed in the URL like other endpoints, but as encoded JSON
     * 
     * @return - the encoded JSON
     *
     */
    private function json_update()
    {
        $input = json_decode(file_get_contents("php://input"));

        if (is_null($input->token)) {
            return json_encode(array("status" => 401, "message" => "Not authorised"));
        }

        if (is_null($input->name) || is_null($input->sessionId)) {
            return json_encode(array("status" => 400, "message" => "Invalid request"));
        }

        try {
            $tokenDecoded = \Firebase\JWT\JWT::decode($input->token, JWTKEY, array('HS256'));
        } catch (UnexpectedValueException $e) {
            return json_encode(array("status" => 401, "message" => $e->getMessage()));
        }

        if ($tokenDecoded->admin == 0) {
            return json_encode(array("status" => 403, "message" => "Not admin"));
        }

        $query = "UPDATE sessions SET name = :name WHERE sessionId = :sessionId";
        $params = ["name" => $input->name, "sessionId" => $input->sessionId];
        $res = $this->recordset->getJSONRecordSet($query, $params);
        return json_encode(array("status" => 200, "message" => "ok"));
    }

    public function get_page()
    {
        return $this->page;
    }
}
