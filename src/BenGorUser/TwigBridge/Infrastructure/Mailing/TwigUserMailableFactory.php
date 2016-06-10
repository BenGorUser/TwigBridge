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

namespace BenGorUser\TwigBridge\Infrastructure\Mailing;

use BenGorUser\User\Domain\Model\UserEmail;
use BenGorUser\User\Domain\Model\UserMailable;
use BenGorUser\User\Domain\Model\UserMailableFactory;

/**
 * Twig implementation of user mailable factory domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
final class TwigUserMailableFactory implements UserMailableFactory
{
    /**
     * The from email address.
     *
     * @var string
     */
    private $from;

    /**
     * The Twig.
     *
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * The view name.
     *
     * @var string
     */
    private $view;

    /**
     * Constructor.
     *
     * @param \Twig_Environment $twig  The Twig environment
     * @param string            $aView The view name
     * @param string            $aFrom The from email address
     */
    public function __construct(\Twig_Environment $twig, $aView, $aFrom)
    {
        $this->twig = $twig;
        $this->view = $aView;
        $this->from = $aFrom;
    }

    /**
     * {@inheritdoc}
     */
    public function build($to, array $parameters = [])
    {
        $template = $this->twig->loadTemplate($this->view);
        $subject = $template->renderBlock('subject', $parameters);
        $bodyText = $template->renderBlock('body_text', $parameters);
        $bodyHtml = $template->renderBlock('body_html', $parameters);

        return new UserMailable($to, new UserEmail($this->from), $subject, $bodyText, $bodyHtml);
    }
}
