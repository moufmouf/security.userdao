<?php
namespace Mouf\Security\Userdao;
use Mouf\Security\UserService\UserDaoInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
class UserDao implements UserDaoInterface {
	/**
	 * 
	 * @var EntityManager
	 */
	protected $entityManager = null;
	/**
	 * 
	 * @var EntityRepository;
	 */
	protected $userRepository = null;
	
	public function __construct(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
		$this->userRepository = $entityManager->getRepository("Coders\\Model\\Entities\\UserEntity");
	}
	
	/**
	 * Returns a user from its login and its password, or null if the login or credentials are false.
	 *
	 * @param string $login
	 * @param string $password
	 * @return UserInterface
	 */
	public function getUserByCredentials($login, $password) {
		//$this->entityManager->find();
		$user = $this->userRepository->findByLogin(
					$login
				);
		if ($user === null || count($user) < 1) {
			return null;
		}
		else if (count($user) > 1)
			throw new UserDaoException("More than one user with this loggin: [".$login."] has been found"); // Or woops excep??
		$pwdHash = $user[0]->getPassword();
		if (password_verify($password, $pwdHash))
			return $user[0];
		return null;
	}
	
	/**
	 * Returns a user from its token.
	 *
	 * @param string $token
	 * @return UserInterface
	*/
	public function getUserByToken($token) {
		$user = $this->userRepository->findByToken(
				$token
		);
		if ($user === null || count($user) < 1)
			return null;
		else if (count($user) > 1)
			throw new UserDaoException("More than one user with this token [".$token."] has been found");
		return $user[0];
	}
	
	/**
	 * Discards a token.
	 *
	 * @param string $token
	*/
	public function discardToken($token){
		$user = $this->userRepository->findByToken(
				$token
		);

		if ($user === null || count($user) < 1)
			return null;
		else if (count($user) > 1)
			throw new UserDaoException("More than one user with this token [".$token."] has been found");
		$user[0]->setToken(null);
		$this->entityManager->fetch();
	}
	
	/**
	 * Returns a user from its ID
	 *
	 * @param string $id
	 * @return UserInterface
	*/
	public function getUserById($id) {
		$user = $this->userRepository->find(
				$id
		);
		
		return $user;
	}
	
	/**
	 * Returns a user from its login
	 *
	 * @param string $login
	 * @return UserInterface
	*/
	public function getUserByLogin($login) {
		$user = $this->userRepository->findByLogin(
				$login
		);
		
		if ($user === null || count($user) < 1)
			return null;
		else if (count($user) > 1)
			throw new UserDaoException("More than one user with this login [".$login."] has been found");
		return $user[0];
	}
	
}