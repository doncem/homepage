<?php
namespace admin\models;

/**
 * User model
 * @Entity
 * @Table(name="adm_user")
 * @package admin_models
 */
class admUser extends \SerializeMyVars {

    /**
     * Super admin
     */
    const ADMIN = "admin";

    /**
     * Regular editor allowed to do mostly editorial text changes
     */
    const EDITOR = "editor";

    /**
     * Autoincrement table ID
     * @var int
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id;

    /**
     * Admin username
     * @var string
     * @Column(type="string", length=100, unique=true)
     */
    protected $username;

    /**
     * Admin email
     * @var string
     * @Column(type="string", length=100, unique=true)
     */
    protected $email;

    /**
     * Admin password
     * @var string
     * @Column(type="string", length=32)
     */
    protected $password;

    /**
     * Admin level
     * @var string
     * @Column(length=6)
     */
    protected $level;

    /**
     * When registered?
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $registered;

    /**
     * Is admin active
     * @var boolean
     * @Column(type="integer", length=1)
     */
    protected $active;

    /**
     * Storing last session ID user had when logged in
     * @var string
     * @Column(length=44, nullable=true)
     */
    protected $last_session;

    /**
     * When last time logged in?
     * @var DateTime
     * @Column(type="datetime", nullable=true)
     */
    protected $last_login;

    /**
     * By default user is not active
     */
    public function __construct() {
        $this->active = false;
    }

    /**
     * Cast to boolean
     * @return boolean
     */
    public function getActive() {
        return (bool)$this->active;
    }

    /**
     * MD5 the password
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = md5($password);
    }
}
