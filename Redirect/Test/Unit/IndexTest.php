<?php

namespace Nortdaniel\Redirect\Test\Unit;


use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Nortdaniel\Redirect\Controller\Message\Index;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;

/**
 *
 */
class IndexTest extends TestCase
{
    /**
     * @var Index | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $indexController;

    /**
     * @var PageFactory | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultPageFactory;

    /**
     * @var Context | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var Page |\PHPUnit_Framework_MockObject_MockObject
     */
    protected $page;

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->resultPageFactory = $this->createMock(PageFactory::class);
        $this->context = $this->createMock(Context::class);
        $this->page = $this->createMock(Page::class);
        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->indexController = $objectManagerHelper->getObject(
            Index::class,
            [
                'context' => $this->context,
                'resultPageFactory' => $this->resultPageFactory
            ]
        );
    }

    /**
     * @return void
     */
    public function testExecute()
    {
        $this->resultPageFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->page);
        $this->assertSame($this->page, $this->indexController->execute());
    }
}
