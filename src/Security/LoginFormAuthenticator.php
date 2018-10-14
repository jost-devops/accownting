<?php declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->encoder = $encoder;
    }

    public function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_security_login');
    }

    public function getCredentials(Request $request)
    {
        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);

        return $form->getData();
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return User|null|UserInterface
     *
     * @SuppressWarnings("unused")
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /**
         * @var User|null $user
         */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['_email']]);

        if ($user === null) {
            throw new AuthenticationException('E-Mail or Password is wrong.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new AuthenticationException('Internal error.');
        }

        if (!$this->encoder->isPasswordValid($user, $credentials['_password'])) {
            throw new AuthenticationException('E-Mail or Password is wrong.');
        }

        return true;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null|\Symfony\Component\HttpFoundation\Response|void
     *
     * @SuppressWarnings("unused")
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    public function supports(Request $request)
    {
        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);

        return $form->isSubmitted() && $form->isValid();
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
