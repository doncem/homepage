<?php

namespace admin\helpers\db;

use Doctrine\Common\Collections\Criteria;
use admin\models\admUser;

/**
 * Admin user helper
 * @package admin_helpers_db
 */
class User extends \DbDoctrineHelper {

    /**
     * Find user
     * @param string $username
     * @param string $password
     * @return admUser|null
     */
    public function getByUsernameAndPassword($username, $password) {
        return $this->em
            ->getRepository("admin\models\admUser")
            ->findOneBy(array(
                "username"  => $username,
                "password"  => md5($password)
            ));
    }

    public function getBySessionID($session_id) {
        return $this->em
            ->getRepository("admin\models\admUser")
            ->matching(Criteria::create()
                ->where(Criteria::expr()->eq("last_session", $session_id))
                ->andWhere(Criteria::expr()->gt("last_login", new \DateTime(
                    date("Y-m-d H:i:s", time() - \admin\helpers\Auth::COOKIE_LIFETIME)
                )))
            )->first();
    }

    /**
     * Set new timing for user. For keeping him not logged out unless he keeps page inactive for longer than allowed
     * @param admUser $user
     */
    public function updateUserLastLogin(admUser $user) {
        $user->last_login = new \DateTime();
        $this->em->beginTransaction();
        $this->em->flush($user);
        $this->em->commit();
    }

    /**
     * Save it
     * @param admUser $user
     */
    public function save(admUser $user) {
        $this->em->beginTransaction();
        $this->em->flush($user);
        $this->em->commit();
    }
}
