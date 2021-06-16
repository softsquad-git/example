<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\UserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @var UserRepository $userRepository
     */
    private UserRepository $userRepository;

    /**
     * @var PaginatorInterface $paginator
     */
    private PaginatorInterface $paginator;

    /**
     * @var UserService $userService
     */
    private UserService $userService;

    public function __construct(
        UserRepository $userRepository,
        PaginatorInterface $paginator,
        UserService $userService
    )
    {
        $this->userRepository = $userRepository;
        $this->paginator = $paginator;
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/users', name: 'users')]
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('users/index.html.twig', [
            'users' => $this->paginator->paginate(
                $this->userRepository->findAll(),
                $request->query->getInt('page', 1), 10
            ),
            'title' => 'All users'
        ]);
    }

    /**
     * @param int $accountId
     * @return RedirectResponse
     */
    #[Route('/user/lock-account/{accountId}', name: 'lock_account')]
    public function lockUnLockAccount(int $accountId): RedirectResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if ($this->getUser()->getIsLocked() == 1)
            return $this->redirectToRoute('users');

        $user = $this->userRepository->find($accountId);

        if (!$user)
            return $this->redirectToRoute('users');

        $this->userService->lockUnLockAccount($user);

        return $this->redirectToRoute('users');
    }
}
