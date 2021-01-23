<?php

/*
 * This file is part of the iidestiny/flysystem-oss.
 *
 * (c) iidestiny <iidestiny@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Iidestiny\Flysystem\Oss\Plugins;

use League\Flysystem\Plugin\AbstractPlugin;

/**
 * Class Kernel.
 */
class Kernel extends AbstractPlugin
{
    /**
     * @return string
     */
    public function getMethod()
    {
        return 'kernel';
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        return $this->filesystem->getAdapter()->getClient();
    }
}
