<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class BlogPostNotFoundException extends Exception
{
	public static function byId(UuidInterface $id, UuidInterface $blogId): self
	{
		return new self('BlogPost with id "' . $id . '" and blog_id "' . $blogId . '" not found.');
	}
}
