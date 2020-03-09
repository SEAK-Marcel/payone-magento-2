<?php

declare(strict_types=1);

/**
 * PAYONE Magento 2 Connector is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PAYONE Magento 2 Connector is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with PAYONE Magento 2 Connector. If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 *
 * @category  Payone
 * @package   Payone_Magento2_Plugin
 * @author    run_as_root GmbH <info@run-as-root.sh>
 * @copyright 2003 - 2017 Payone GmbH
 * @license   <http://www.gnu.org/licenses/> GNU Lesser General Public License
 * @link      http://www.payone.de
 */

namespace Payone\Core\Test\Unit\Model\Plugins;

use Magento\Framework\Event\Observer;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Payone\Core\Model\Methods\PayoneMethod;
use Payone\Core\Model\Plugins\GenerateGiftCardAccountsInvoice;
use PHPUnit\Framework\MockObject\MockObject as Mock;
use PHPUnit\Framework\TestCase;

use const Payone\Core\Model\Methods\PayoneMethod;

class GenerateGiftCardAccountsInvoiceTest extends TestCase
{
    /**
     * System under Test
     *
     * @var
     */
    private $sut;

    protected function setUp() : void
    {
        $this->sut = new GenerateGiftCardAccountsInvoice();
    }

    public function test_it_should_execute_successfully() : void
    {
        $paymentMethodMock = $this->createMock(PayoneMethod::class);

        $paymentMock = $this->getMockBuilder(OrderPaymentInterface::class)->disableOriginalConstructor()->setMethods(['getMethodInstance'])->getMockForAbstractClass();
        $paymentMock->expects($this->once())->method('getMethodInstance')->willReturn($paymentMethodMock);

        $orderMock = $this->createMock(Order::class);
        $orderMock->expects($this->once())->method('getPayment')->willReturn($paymentMock);

        $invoiceMock = $this->createMock(Invoice::class);
        $invoiceMock->expects($this->once())->method('getOrder')->willReturn($orderMock);

        $subjectMock = $this->createMock(\Magento\GiftCard\Observer\GenerateGiftCardAccountsInvoice::class);
        $proceedMock = static function ($observer) {};

        /** @var Observer | Mock $observerMock */
        $observerMock = $this->getMockBuilder(Observer::class)->disableOriginalConstructor()->setMethods(['getInvoice'])->getMockForAbstractClass();
        $observerMock->expects($this->once())->method('getInvoice')->willReturn($invoiceMock);

        $actual = $this->sut->aroundExecute($subjectMock, $proceedMock, $observerMock);
        $expected = null;

        $this->assertEquals($expected, $actual);
    }
}
