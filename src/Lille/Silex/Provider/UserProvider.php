<?php

namespace Lille\Silex\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\DBAL\Connection;
use Monolog\Logger;

class UserProvider implements UserProviderInterface
{
    /** @var Connection */
    private $conn;

    /** @var Logger */
    private $logger;


    /**
     * @param Connection $conn
     */
    public function __construct(Connection $conn) {
        $this->conn   = $conn;
    }

    /**
     * Use email instead
     *
     * @param string $username
     *
     * @return UserInterface
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username) {
        /**
         * @var \PDOStatement $stmt
         */
        $email = filter_var(strtolower($username), FILTER_SANITIZE_EMAIL);

        $stmt = $this->conn->executeQuery('SELECT * FROM user WHERE email = ?', array($email));

        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return new User($user['email'], $user['password'], explode(',', $user['roles']), true, true, true, true);
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     * @throws UnsupportedUserException
     */
    public function refreshUser(UserInterface $user) {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class) {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}
