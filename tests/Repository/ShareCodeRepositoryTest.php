<?php

namespace WechatMiniProgramShareBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatMiniProgramShareBundle\Entity\ShareCode;
use WechatMiniProgramShareBundle\Repository\ShareCodeRepository;

/**
 * @internal
 */
#[CoversClass(ShareCodeRepository::class)]
#[RunTestsInSeparateProcesses]
final class ShareCodeRepositoryTest extends AbstractRepositoryTestCase
{
    private ShareCodeRepository $repository;

    protected function createNewEntity(): object
    {
        $entity = new ShareCode();

        // 设置必填字段
        $entity->setLinkUrl('https://example.com/test/' . uniqid());

        return $entity;
    }

    protected function getRepository(): ShareCodeRepository
    {
        return $this->repository;
    }

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ShareCodeRepository::class);
    }

    public function testSave(): void
    {
        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/save-test');

        $this->repository->save($entity);

        $this->assertIsInt($entity->getId());
        $this->assertGreaterThan(0, $entity->getId());
    }

    public function testRemove(): void
    {
        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/remove-test');

        $this->repository->save($entity);
        $id = $entity->getId();

        $this->repository->remove($entity);

        $result = $this->repository->find($id);
        $this->assertNull($result);
    }

    public function testFindByValidTrue(): void
    {
        $result = $this->repository->findByValidTrue();

        $this->assertIsArray($result);
        $this->assertContainsOnlyInstancesOf(ShareCode::class, $result);
    }

    public function testFindByWithAccountAssociation(): void
    {
        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/account-test');
        $entity->setAccount(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['account' => null]);

        $this->assertIsArray($result);
        $this->assertGreaterThan(0, \count($result));
    }

    public function testCountWithAccountAssociation(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/count-account-test');
        $entity->setAccount(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['account' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithValidIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/valid-null');
        $entity->setValid(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['valid' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->isValid());
    }

    public function testCountWithValidIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/count-valid-null');
        $entity->setValid(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['valid' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithCreatedByAssociation(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/created-by-test');
        $entity->setCreatedBy(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['createdBy' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getCreatedBy());
    }

    public function testCountWithCreatedByAssociation(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/count-created-by');
        $entity->setCreatedBy(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['createdBy' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithUpdatedByAssociation(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/updated-by-test');
        $entity->setUpdatedBy(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['updatedBy' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getUpdatedBy());
    }

    public function testCountWithUpdatedByAssociation(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/count-updated-by');
        $entity->setUpdatedBy(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['updatedBy' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithUserAssociation(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/user-association-test');
        $entity->setUser(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['user' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getUser());
    }

    public function testCountWithUserAssociation(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/count-user-association');
        $entity->setUser(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['user' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithCreatedFromIpIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/ip-null');
        $entity->setCreatedFromIp(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['createdFromIp' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getCreatedFromIp());
    }

    public function testCountWithCreatedFromIpIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/count-ip-null');
        $entity->setCreatedFromIp(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['createdFromIp' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindByWithUpdatedFromIpIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/update-ip-null');
        $entity->setUpdatedFromIp(null);

        $this->repository->save($entity);

        $result = $this->repository->findBy(['updatedFromIp' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getUpdatedFromIp());
    }

    public function testCountWithUpdatedFromIpIsNull(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity = new ShareCode();
        $entity->setLinkUrl('https://example.com/count-update-ip-null');
        $entity->setUpdatedFromIp(null);

        $this->repository->save($entity);

        $count = $this->repository->count(['updatedFromIp' => null]);

        $this->assertEquals(1, $count);
    }

    public function testFindOneByShouldRespectOrderByClause(): void
    {
        $this->repository->createQueryBuilder('s')
            ->delete()
            ->getQuery()
            ->execute()
        ;

        $entity1 = new ShareCode();
        $entity1->setLinkUrl('https://example.com/findone-order-a');
        $entity1->setValid(true);

        $entity2 = new ShareCode();
        $entity2->setLinkUrl('https://example.com/findone-order-b');
        $entity2->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);

        $result = $this->repository->findOneBy(['valid' => true], ['id' => 'ASC']);
        $this->assertNotNull($result);
        $this->assertEquals($entity1->getId(), $result->getId());

        $result = $this->repository->findOneBy(['valid' => true], ['id' => 'DESC']);
        $this->assertNotNull($result);
        $this->assertEquals($entity2->getId(), $result->getId());

        $result = $this->repository->findOneBy(['valid' => true], ['linkUrl' => 'ASC']);
        $this->assertNotNull($result);
        $this->assertEquals('https://example.com/findone-order-a', $result->getLinkUrl());

        $result = $this->repository->findOneBy(['valid' => true], ['linkUrl' => 'DESC']);
        $this->assertNotNull($result);
        $this->assertEquals('https://example.com/findone-order-b', $result->getLinkUrl());
    }
}
