<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
// use App\Service\AvatarHelper;
// use App\Service\TelegramAdminMessageHelper;
// use App\Service\TelegramBotHelper;
// use App\Service\TelegramMessageHelper;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
// use TelegramBot\Api\Exception;
// use TelegramBot\Api\InvalidArgumentException;

class GoogleAuthenticator extends SocialAuthenticator
{
    use TargetPathTrait;

    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $entityManager;
    private UserRepository $userManager;
    // private TelegramBotHelper $telegramBotHelper;
    private UrlGeneratorInterface $urlGenerator;
    // private AvatarHelper $avatarHelper;
    // private TelegramMessageHelper $telegramMessageHelper;
    // private TelegramAdminMessageHelper $telegramAdminMessageHelper;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
        // TelegramBotHelper $telegramBotHelper,
        // TelegramAdminMessageHelper $telegramAdminMessageHelper,
        // AvatarHelper $avatarHelper
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        // $this->telegramBotHelper = $telegramBotHelper;
        $this->urlGenerator = $urlGenerator;
        // $this->avatarHelper = $avatarHelper;
        // $this->telegramAdminMessageHelper = $telegramAdminMessageHelper;
    }

    public function supports(Request $request): bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    /**
     * @return AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        return $this->fetchAccessToken($this->getGoogleClient());
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var GoogleUser $googleUser */
        $googleUser = $this->getGoogleClient()
            ->fetchUserFromToken($credentials);

        $userRepository = $this->entityManager->getRepository(User::class);

        // Fetch user by google id
        $user = $userRepository->findOneBy(
            ['googleId' => $googleUser->getId()]
        );

        if (!$user) {
            // Fetch user by email - @todo remove
            $user = $userRepository->findOneBy(
                ['email' => $googleUser->getEmail()]
            );
            if (!$user) {
                // Register new user
                $newUser = true;
                $user = (new User())
                    ->setEmail($googleUser->getEmail())
                    ->setGoogleId($googleUser->getId());
            } else {
                $newUser = false;
                // Update existing users google id - @todo remove
                $user->setGoogleId($googleUser->getId());
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            if ($newUser) {
                // $groupId = $this->telegramBotHelper->getGroupId('admin');
                // $this->telegramAdminMessageHelper->sendNewUserMessage($groupId, $user);
            }
        }

        if (true){//!$user->getAvatar()) {
            // Update avatar
            // $user->setAvatar($googleUser->getAvatar());
            // $this->avatarHelper->updateAvatar($user);
            // $this->entityManager->persist($user);
            // $this->entityManager->flush();
        }

        return $user;
    }

    /**
     * @return GoogleClient
     */
    private function getGoogleClient()
    {
        return $this->clientRegistry->getClient('google');
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ): RedirectResponse {
        $targetPath = $this->getTargetPath(
            $request->getSession(),
            $providerKey
        );

        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('default'));
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): Response {
        $message = strtr(
            $exception->getMessageKey(),
            $exception->getMessageData()
        );

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(
        Request $request,
        AuthenticationException $authException = null
    ): RedirectResponse {
        return new RedirectResponse(
            '/connect/',
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}
