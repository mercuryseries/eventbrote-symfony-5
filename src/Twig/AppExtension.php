<?php

namespace App\Twig;

use Twig\TwigFilter;
use App\Entity\Event;
use Twig\TwigFunction;
use Twig\Extra\Intl\IntlExtension;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Routing\RouterInterface;

class AppExtension extends AbstractExtension
{
    private $intlExtension;
    private $router;

    public function __construct(IntlExtension $intlExtension, RouterInterface $router)
    {
        $this->intlExtension = $intlExtension;
        $this->router = $router;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('datetime', [$this, 'formatDateTime']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('format_price', [$this, 'formatPrice'], ['is_safe' => ['html']]),
            new TwigFunction('pluralize', [$this, 'pluralize']),
            new TwigFunction('register_link_or_sold_out', [$this, 'registerLinkOrSoldOut'], ['is_safe' => ['html']]),
        ];
    }

    public function formatPrice(Event $event): string
    {
        return $event->isFree() 
            ? '<span class="badge badge-primary">Free!</span>'
            : $this->intlExtension->formatCurrency($event->getPrice(), 'USD');
    }

    public function formatDateTime(\DateTimeInterface $dateTime): string
    {
        return $dateTime->format('F d, Y \\a\\t h:i A');
    }

    public function pluralize(int $count, string $singular, string $plural = null): string
    {
        $plural ??= $singular . 's';

        $string = $count === 1 ? $singular : $plural;

        return "$count $string";
    }

    public function registerLinkOrSoldOut(Event $event): string
    {
        return $event->isSoldOut() 
            ? '<div class="h5"><span class="badge badge-danger text-uppercase">Sold Out!</span></div>'
            : sprintf(
                '<p>
                    <a href="%s" class="btn btn-primary btn-block text-uppercase font-weight-bold">
                        📅 Register to this event
                    </a>
                </p>',
                $this->router->generate('events.registrations.create', ['event' => $event->getId()])
            );
    }
}
