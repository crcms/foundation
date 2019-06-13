<?php

namespace CrCms\Foundation\Jobs;

use Throwable;
use Illuminate\Contracts\Queue\Job;

abstract class AbstractJob
{
    /**
     * 触发队列方法.
     *
     * 如果有错误或者因错误终止执行，请使用异常Exception抛出，这样队列经过最大重试之后会自动删除
     * 不可用return来终止执行否则下次启动队列会重复投递执行，因为队列状态为Unacked
     *
     * 在成功执行之后必须调用$job->delete();来进行删除些队列，否则下次启动队列会重复投递执行，因为队列状态为Unacked
     *
     * @param Job $job
     * @param array
     * @return void
     */
    public function fire(Job $job, array $data): void
    {
        $this->handle($data, $job);

        $job->delete();
    }

    /**
     * @param array $data
     * @param Job $job
     *
     * @return void
     */
    abstract protected function handle(array $data, Job $job): void;

    /**
     * 执行失败后执行此方法.
     *
     * @param array $data
     * @param Throwable $e
     *
     * @return void
     *
     * @throws Throwable
     */
    public function failed(array $data, Throwable $e): void
    {
        throw $e;
    }
}
