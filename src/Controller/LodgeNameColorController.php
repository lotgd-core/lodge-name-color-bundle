<?php

/**
 * This file is part of "LoTGD Bundle Name Color".
 *
 * @see https://github.com/lotgd-core/lodge-name-color-bundle
 *
 * @license https://github.com/lotgd-core/lodge-name-color-bundle/blob/master/LICENSE.txt
 * @author IDMarinas
 *
 * @since 0.1.0
 */

namespace Lotgd\Bundle\LodgeNameColorBundle\Controller;

use Lotgd\Bundle\LodgeNameColorBundle\Event\NameColorEvent;
use Lotgd\Bundle\LodgeNameColorBundle\Form\NameColorType;
use Lotgd\Bundle\LodgeNameColorBundle\LotgdLodgeNameColorBundle;
use Lotgd\Bundle\LodgeNameColorBundle\Pattern\ModuleUrlTrait;
use Lotgd\Core\Http\Request;
use Lotgd\Core\Http\Response as HttpResponse;
use Lotgd\Core\Log;
use Lotgd\Core\Navigation\Navigation;
use Lotgd\Core\Tool\Sanitize;
use Lotgd\Core\Tool\Tool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class LodgeNameColorController extends AbstractController
{
    use ModuleUrlTrait;

    public const TRANSLATION_DOMAIN = LotgdLodgeNameColorBundle::TRANSLATION_DOMAIN;

    private $tool;
    private $response;
    private $navigation;
    private $translator;
    private $sanitize;
    private $parameter;
    private $dispatcher;
    private $log;

    public function __construct(
        Tool $tool,
        HttpResponse $response,
        Navigation $navigation,
        TranslatorInterface $translator,
        Sanitize $sanitize,
        ParameterBagInterface $parameter,
        EventDispatcherInterface $dispatcher,
        Log $log
    ) {
        $this->tool       = $tool;
        $this->response   = $response;
        $this->navigation = $navigation;
        $this->translator = $translator;
        $this->sanitize   = $sanitize;
        $this->parameter  = $parameter;
        $this->dispatcher = $dispatcher;
        $this->log        = $log;
    }

    public function enter(Request $request): Response
    {
        require_once 'lib/names.php';

        global $session;

        $times           = (int) get_module_pref('times_purchased', 'lodge_name_color');
        $cost            = $this->parameter->get('lotgd_bundle.lodge_name_color.cost.first');
        $pointsAvailable = $session['user']['donation'] - $session['user']['donationspent'];

        if ($times)
        {
            $cost = $this->parameter->get('lotgd_bundle.lodge_name_color.cost.other');
        }

        $params = [
            'cost'             => $cost,
            'points_available' => $pointsAvailable,
            'extra_points'     => $this->parameter->get('lotgd_bundle.lodge_name_color.cost.other'),
            'reg_name'         => $this->getPlayerCBasename(),
            'is_preview'       => false,
            'is_name_colorize' => false,
        ];

        //-- If they have less than what they need just ignore them
        if ($cost > $pointsAvailable)
        {
            return $this->render('@LotgdLodgeNameColor/no_donator_points.html.twig', $params);
        }

        $form = $this->createForm(NameColorType::class, ['new_name' => $params['reg_name']], [
            'action' => $this->getModuleUrl('enter'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data    = $form->getData();
            $newName = $data['new_name'];

            if ($form->get('button_preview')->isClicked())
            {
                $params['is_preview'] = true;

                $params['new_name'] = $newName;
            }
            elseif ($form->get('button_change')->isClicked())
            {
                $fromName = $session['user']['playername'];
                $newName  = $this->tool->changePlayerName($newName);

                $many = $times ? 'another' : 'first';
                $this->log->debug("bought {$many} custom title for {$cost} points");

                $event = new NameColorEvent();
                $event->setFromName($fromName)
                    ->setNewName($newName)
                ;

                $this->dispatcher->dispatch($event, NameColorEvent::NAME_COLORIZE);

                $newName                 = $event->getNewName();
                $session['user']['name'] = $newName;

                $this->tool->addNews('news.changed', [
                    'from' => $fromName,
                    'new'  => $session['user']['name'],
                ], self::TRANSLATION_DOMAIN);

                set_module_pref('times_purchased', $times + 1, 'lodge_name_color');
                ++$times;

                $session['user']['donationspent'] += $cost;

                $params['name']             = $newName;
                $params['is_name_colorize'] = true;
            }
        }

        $params['times'] = $times;
        $params['form']  = $form->createView();

        return $this->render('@LotgdLodgeNameColor/enter.html.twig', $params);
    }

    protected function render(string $view, array $params = [], ?Response $response = null): Response
    {
        $this->response->pageTitle('title', [], self::TRANSLATION_DOMAIN);

        $this->navigation->addNav('navigation.nav.return', 'lodge.php', ['textDomain' => self::TRANSLATION_DOMAIN]);

        $params['translation_domain'] = self::TRANSLATION_DOMAIN;

        return parent::render($view, $params, $response);
    }

    private function getPlayerCBasename($old = null): string
    {
        global $session;

        $name  = $session['user']['name'];
        $title = $this->tool->getPlayerTitle($old);

        if ( ! empty($old))
        {
            $name = $old['name'];
        }

        if ($title)
        {
            $x = strpos($name, $title);

            if (false !== $x)
            {
                $name = trim(substr($name, $x + \strlen($title)));
            }
        }

        return $name;
    }
}
