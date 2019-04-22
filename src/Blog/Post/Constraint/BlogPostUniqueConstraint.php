<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post\Constraint;

use Ramsey\Uuid\UuidInterface;

final class BlogPostUniqueConstraint
{
	/** @var UuidInterface */
	private $id;

	/** @var UuidInterface */
	private $blogId;

	public function __construct(UuidInterface $id, UuidInterface $blogId)
	{
		$this->id = $id;
		$this->blogId = $blogId;
	}

	public function getId(): UuidInterface
	{
		return $this->id;
	}

	public function getBlogId(): UuidInterface
	{
		return $this->blogId;
	}
}