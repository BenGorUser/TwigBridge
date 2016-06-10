<?php

/*
 * This file is part of the BenGorUser package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\BenGorUser\TwigBridge\Infrastructure\Mailing;

use BenGorUser\TwigBridge\Infrastructure\Mailing\TwigUserMailableFactory;
use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserMailable;
use BenGorUser\User\Domain\Model\UserMailableFactory;
use PhpSpec\ObjectBehavior;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Translation\Translator;

/**
 * Spec file of TwigUserMailableFactory class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TwigUserMailableFactorySpec extends ObjectBehavior
{
    function let()
    {
        $loader = new \Twig_Loader_Filesystem(
            __DIR__ . '/../../../../../src/BenGorUser/TwigBridge/Infrastructure/Ui/Twig/views'
        );
        $twig = new \Twig_Environment($loader);
        $twig->addExtension(new TranslationExtension(
            new Translator('en_US')
        ));

        $this->beConstructedWith($twig, 'Email/sign_up.html.twig', 'bengor@user.com');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TwigUserMailableFactory::class);
    }

    function it_implements_user_mailable_factory()
    {
        $this->shouldImplement(UserMailableFactory::class);
    }

    function it_builds()
    {
        $to = new UserEmail('bengor@user.com');

        $this->build($to, [])->shouldReturnAnInstanceOf(UserMailable::class);
    }

    function it_builds_with_multiples_receivers()
    {
        $to = [
            new UserEmail('bengor@user.com'),
            new UserEmail('gorka.lauzirika@gmail.com'),
            new UserEmail('benatespina@gmail.com'),
        ];

        $this->build($to, [])->shouldReturnAnInstanceOf(UserMailable::class);
    }
}
