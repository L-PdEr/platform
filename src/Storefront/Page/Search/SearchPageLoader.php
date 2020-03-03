<?php declare(strict_types=1);

namespace Shopware\Storefront\Page\Search;

use Shopware\Core\Content\Category\Exception\CategoryNotFoundException;
use Shopware\Core\Content\Product\SalesChannel\ProductSearchRouteInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\Routing\Exception\MissingRequestParameterException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Page\StorefrontSearchResult;
use Shopware\Storefront\Page\GenericPageLoaderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class SearchPageLoader
{
    /**
     * @var GenericPageLoaderInterface
     */
    private $genericLoader;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ProductSearchRouteInterface
     */
    private $productSearchRoute;

    public function __construct(
        GenericPageLoaderInterface $genericLoader,
        ProductSearchRouteInterface $productSearchRoute,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->genericLoader = $genericLoader;
        $this->productSearchRoute = $productSearchRoute;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws CategoryNotFoundException
     * @throws InconsistentCriteriaIdsException
     * @throws MissingRequestParameterException
     */
    public function load(Request $request, SalesChannelContext $salesChannelContext): SearchPage
    {
        $page = $this->genericLoader->load($request, $salesChannelContext);
        $page = SearchPage::createFrom($page);

        if (!$request->query->has('search')) {
            throw new MissingRequestParameterException('search');
        }

        $result = $this->productSearchRoute->load($request, $salesChannelContext)->getListingResult();

        $page->setListing($result);
        $page->setSearchResult(StorefrontSearchResult::createFrom($result));

        $page->setSearchTerm(
            (string) $request->query->get('search')
        );

        $this->eventDispatcher->dispatch(
            new SearchPageLoadedEvent($page, $salesChannelContext, $request)
        );

        return $page;
    }
}
