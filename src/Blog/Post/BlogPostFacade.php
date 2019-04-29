<?php

declare(strict_types=1);

namespace Rixafy\Blog\Post;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Strings;
use Ramsey\Uuid\UuidInterface;
use Rixafy\Blog\Publisher\BlogPublisherRepository;
use Rixafy\Blog\Publisher\Exception\BlogPublisherNotFoundException;
use Rixafy\Routing\Route\Exception\RouteNotFoundException;
use Rixafy\Routing\Route\RouteGenerator;

class BlogPostFacade
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var BlogPublisherRepository */
    private $blogPublisherRepository;

    /** @var BlogPostRepository */
    private $blogPostRepository;

    /** @var BlogPostFactory */
    private $blogPostFactory;

	/** @var RouteGenerator */
	private $routeGenerator;

    public function __construct(
		EntityManagerInterface $entityManager,
		BlogPublisherRepository $blogRepository,
		BlogPostRepository $blogPostRepository,
		BlogPostFactory $blogPostFactory,
		RouteGenerator $routeGenerator
	) {
        $this->blogPublisherRepository = $blogRepository;
        $this->entityManager = $entityManager;
        $this->blogPostRepository = $blogPostRepository;
        $this->blogPostFactory = $blogPostFactory;
		$this->routeGenerator = $routeGenerator;
	}

    /**
     * @throws BlogPublisherNotFoundException
     */
    public function create(BlogPostData $blogPostData): BlogPost
    {
        $post = $blogPostData->publisher->publish($blogPostData, $this->blogPostFactory);

        $this->entityManager->flush();

        return $post;
    }

    /**
     * @throws Exception\BlogPostNotFoundException
     */
    public function edit(UuidInterface $id, UuidInterface $blogId, BlogPostData $blogPostData): BlogPost
    {
        $post = $this->blogPostRepository->get($id, $blogId);
        $post->edit($blogPostData);

		try {
			$this->routeGenerator->update($post->getId(), Strings::webalize($post->getTitle()));
		} catch (RouteNotFoundException $e) {
		}

		$this->entityManager->flush();

        return $post;
    }

    /**
     * @throws Exception\BlogPostNotFoundException
     */
    public function get(UuidInterface $id, UuidInterface $blogId): BlogPost
    {
        return $this->blogPostRepository->get($id, $blogId);
    }

    /**
     * @throws Exception\BlogPostNotFoundException
     */
    public function remove(UuidInterface $id, UuidInterface $blogId, bool $permanent = false): void
    {
        $post = $this->get($id, $blogId);

        if ($permanent) {
            $this->entityManager->remove($post);

        } else {
            $post->remove();
        }

        $this->entityManager->flush();
    }
}
