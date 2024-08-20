<?php

class Session {
    private array $messages;

    public function __construct() {
      session_start();
      //print_r($_SESSION);

      $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
      unset($_SESSION['messages']);
    }

    public function login(int $id) {
      session_regenerate_id(true);
      $_SESSION['id'] = $id;
      $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    public function isLoggedIn() : bool {
      return isset($_SESSION['id']);    
    }

    public function logout() {
      $_SESSION = [];
      session_destroy();
    }

    public function getId() : ?int {
      return isset($_SESSION['id']) ? $_SESSION['id'] : null;    
    }

    public function getName() : ?string {
      return isset($_SESSION['name']) ? $_SESSION['name'] : null;
    }

    public function getCsrf() :?string {
      return isset($_SESSION['csrf']) ? $_SESSION['csrf'] : null; 
    }

    public function checkCsrf(string $token) : bool {
      if (!isset($_SESSION['csrf']) || $_SESSION['csrf'] !== $token) {
        return false;
      }
      $_SESSION['csrf'] = bin2hex(random_bytes(32)); 
      return true;
    }

    public function setId(int $id) {
      $_SESSION['id'] = $id;
    }

    public function setName(string $name) {
      $_SESSION['name'] = $name;
    }

    public function addMessage(string $type, string $text) {
      $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function getMessages() {
      return $this->messages;
    }
  }
?>