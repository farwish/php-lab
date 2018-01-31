<?php

/**
 * Event unique name.
 *
 * @license Apache-2.0
 * @author farwish <farwish@foxmail.com>
 */

class Events
{
    // 唯一的事件名
    //
    // 命名惯例：
	// Use only lowercase letters, numbers, dots (.) and underscores (_);
	// Prefix names with a namespace followed by a dot (e.g. order., user.*);
	// End names with a verb that indicates what action has been taken (e.g. order.placed).

    const PAY = 'events.pay';

    const REFUND = 'events.refund';
}
