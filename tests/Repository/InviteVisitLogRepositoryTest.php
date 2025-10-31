<?php

namespace WechatMiniProgramShareBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramShareBundle\Entity\InviteVisitLog;
use WechatMiniProgramShareBundle\Repository\InviteVisitLogRepository;

/**
 * @internal
 */
#[CoversClass(InviteVisitLogRepository::class)]
#[RunTestsInSeparateProcesses]
final class InviteVisitLogRepositoryTest extends AbstractRepositoryTestCase
{
    private InviteVisitLogRepository $repository;

    protected function createNewEntity(): object
    {
        $entity = new InviteVisitLog();

        // 设置必填字段
        $entity->setShareOpenId('test_share_' . uniqid());
        $entity->setVisitOpenId('test_visit_' . uniqid());
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/path/' . uniqid());

        return $entity;
    }

    protected function getRepository(): InviteVisitLogRepository
    {
        return $this->repository;
    }

    protected function onSetUp(): void
    {
        $this->repository = self::getService(InviteVisitLogRepository::class);
    }

    public function testSave(): void
    {
        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_save_share');
        $entity->setVisitOpenId('test_save_visit');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/save/path');

        $this->repository->save($entity);

        $this->assertIsString($entity->getId());
        $this->assertNotEmpty($entity->getId());
    }

    public function testRemove(): void
    {
        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_remove_share');
        $entity->setVisitOpenId('test_remove_visit');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/remove/path');

        $this->repository->save($entity);
        $id = $entity->getId();

        $this->repository->remove($entity);

        $result = $this->repository->find($id);
        $this->assertNull($result);
    }

    public function testFindByNewUserTrue(): void
    {
        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_share');
        $entity->setVisitOpenId('test_visit');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/path');
        $entity->setNewUser(true);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['newUser' => true]);

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(InviteVisitLog::class, $result);
        $this->assertGreaterThan(0, \count($result));
    }

    public function testFindByWithShareUserAssociation(): void
    {
        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_share_user');
        $entity->setVisitOpenId('test_visit_user');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/user/path');
        $entity->setShareUser(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['shareUser' => null]);

        $this->assertIsArray($result);
        $this->assertGreaterThan(0, \count($result));
    }

    public function testCountWithShareUserAssociation(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_count_share_user');
        $entity->setVisitOpenId('test_count_visit_user');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/count/user/path');
        $entity->setShareUser(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['shareUser' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithVisitUserAssociation(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_visit_user_assoc');
        $entity->setVisitOpenId('test_visit_user_assoc');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/visit/user/assoc');
        $entity->setVisitUser(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['visitUser' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getVisitUser());
    }

    public function testCountWithVisitUserAssociation(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_count_visit_user');
        $entity->setVisitOpenId('test_count_visit_user');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/count/visit/user');
        $entity->setVisitUser(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['visitUser' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithRegisteredIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_registered_null');
        $entity->setVisitOpenId('test_visit_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/null/path');
        $entity->setRegistered(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['registered' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->isRegistered());
    }

    public function testCountWithRegisteredIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_count_registered_null');
        $entity->setVisitOpenId('test_count_visit_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/count/null/path');
        $entity->setRegistered(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['registered' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithContextIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_context_null');
        $entity->setVisitOpenId('test_visit_context_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/context/null');
        $entity->setContext(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['context' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getContext());
    }

    public function testCountWithContextIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_count_context_null');
        $entity->setVisitOpenId('test_count_visit_context_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/count/context/null');
        $entity->setContext(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['context' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithCreatedFromIpIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_ip_null');
        $entity->setVisitOpenId('test_visit_ip_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/ip/null');
        $entity->setCreatedFromIp(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['createdFromIp' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getCreatedFromIp());
    }

    public function testCountWithCreatedFromIpIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_count_ip_null');
        $entity->setVisitOpenId('test_count_visit_ip_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/count/ip/null');
        $entity->setCreatedFromIp(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['createdFromIp' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithUpdatedFromIpIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_update_ip_null');
        $entity->setVisitOpenId('test_visit_update_ip_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/update/ip/null');
        $entity->setUpdatedFromIp(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['updatedFromIp' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getUpdatedFromIp());
    }

    public function testCountWithUpdatedFromIpIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_count_update_ip_null');
        $entity->setVisitOpenId('test_count_visit_update_ip_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/count/update/ip/null');
        $entity->setUpdatedFromIp(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['updatedFromIp' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithLaunchOptionsIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_launch_null');
        $entity->setVisitOpenId('test_visit_launch_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/launch/null');
        $entity->setLaunchOptions(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['launchOptions' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getLaunchOptions());
    }

    public function testCountWithLaunchOptionsIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_count_launch_null');
        $entity->setVisitOpenId('test_count_visit_launch_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/count/launch/null');
        $entity->setLaunchOptions(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['launchOptions' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithEnterOptionsIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_enter_null');
        $entity->setVisitOpenId('test_visit_enter_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/enter/null');
        $entity->setEnterOptions(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['enterOptions' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getEnterOptions());
    }

    public function testCountWithEnterOptionsIsNull(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new InviteVisitLog();
        $entity->setShareOpenId('test_count_enter_null');
        $entity->setVisitOpenId('test_count_visit_enter_null');
        $entity->setShareTime(new \DateTimeImmutable());
        $entity->setVisitTime(new \DateTimeImmutable());
        $entity->setVisitPath('/test/count/enter/null');
        $entity->setEnterOptions(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['enterOptions' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindOneByShouldRespectOrderByClause(): void
    {
        $this->repository->createQueryBuilder('i')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $time1 = new \DateTimeImmutable('2023-01-01 10:00:00');
        $time2 = new \DateTimeImmutable('2023-01-02 10:00:00');

        $entity1 = new InviteVisitLog();
        $entity1->setShareOpenId('findone_order_share_a');
        $entity1->setVisitOpenId('findone_order_visit_1');
        $entity1->setShareTime($time1);
        $entity1->setVisitTime($time1);
        $entity1->setVisitPath('/test/findone/order/1');
        $entity1->setNewUser(true);

        $entity2 = new InviteVisitLog();
        $entity2->setShareOpenId('findone_order_share_b');
        $entity2->setVisitOpenId('findone_order_visit_2');
        $entity2->setShareTime($time2);
        $entity2->setVisitTime($time2);
        $entity2->setVisitPath('/test/findone/order/2');
        $entity2->setNewUser(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy(['newUser' => true], ['shareTime' => 'ASC']);
        $this->assertNotNull($result);
        $this->assertEquals($time1, $result->getShareTime());

        $result = $this->repository->findOneBy(['newUser' => true], ['shareTime' => 'DESC']);
        $this->assertNotNull($result);
        $this->assertEquals($time2, $result->getShareTime());

        $result = $this->repository->findOneBy(['newUser' => true], ['shareOpenId' => 'ASC']);
        $this->assertNotNull($result);
        $this->assertEquals('findone_order_share_a', $result->getShareOpenId());

        $result = $this->repository->findOneBy(['newUser' => true], ['shareOpenId' => 'DESC']);
        $this->assertNotNull($result);
        $this->assertEquals('findone_order_share_b', $result->getShareOpenId());
    }
}
