<?php

namespace KingFoo\BookmarkerBundle\Controller;

use KingFoo\BookmarkerBundle\Entity\Bookmark;
use KingFoo\BookmarkerBundle\Form\Type\BookmarkType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * Landing page.
     *
     * @Route("/", name="bookmarker_index")
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('bookmarker_feed'));
    }

    /**
     * Display a feed of the latest bookmarks for all users.
     *
     * @Route("/feed/{page}", name="bookmarker_feed", requirements={"page"="[1-9]\d*"})
     * @Template
     */
    public function feedAction($page = 1)
    {
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $repo = $this->getDoctrine()->getRepository('KingFooBookmarkerBundle:Bookmark');
        $bookmarks = $repo->findFeed($limit, $offset);

        $totalPages = ceil(count($bookmarks) / $limit);

        return array(
            'bookmarks' => $bookmarks,
            'page' => $page,
            'totalPages' => $totalPages
        );
    }

    /**
     * Display the bookmarks for a specific user.
     *
     * @Route("/users/{username}/{page}", name="bookmarker_user", requirements={"page"="[1-9]\d*"})
     * @Template
     */
    public function userAction($username, $page = 1)
    {
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $repo = $this->getDoctrine()->getRepository('KingFooBookmarkerBundle:Bookmark');
        $bookmarks = $repo->findForUsername($username, $limit, $offset);

        $totalPages = ceil(count($bookmarks) / $limit);

        return array(
            'bookmarks' => $bookmarks,
            'username' => $username,
            'page' => $page,
            'totalPages' => $totalPages
        );
    }

    /**
     * Display the bookmarks for the currently authenticated user.
     *
     * @Route("/me/{page}", name="bookmarker_me", requirements={"page"="[1-9]\d*"})
     * @Template
     */
    public function meAction($page = 1)
    {
        $user = $this->getUser();

        return $this->forward('KingFooBookmarkerBundle:Default:user', array('username' => $user->getUsername(), 'page' => $page));
    }

    /**
     * Display all bookmarks for a specific tag.
     *
     * @Route("/tags/{tag}/{page}", name="bookmarker_tag", requirements={"page"="[1-9]\d*"})
     * @Template
     */
    public function tagAction($tag, $page = 1)
    {
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $repo = $this->getDoctrine()->getRepository('KingFooBookmarkerBundle:Bookmark');

        $bookmarks = $repo->findForTag($tag, $limit, $offset);
        $totalPages = ceil(count($bookmarks) / $limit);

        return array(
            'bookmarks' => $bookmarks,
            'tag' => $tag,
            'page' => $page,
            'totalPages' => $totalPages
        );
    }

    /**
     * Display the 20 most bookmarked urls of the past month.
     *
     * @Route("/popular", name="bookmarker_popular")
     * @Template
     */
    public function popularAction()
    {
        $limit = 20;

        $repo = $this->getDoctrine()->getRepository('KingFooBookmarkerBundle:Bookmark');

        $bookmarks = $repo->findPopular($limit);

        return array(
            'bookmarks' => $bookmarks
        );
    }

    /**
     * Create a new bookmark.
     *
     * @Route("/me/bookmark", name="bookmarker_create")
     * @Template
     */
    public function createAction(Request $request)
    {
        $user = $this->getUser();

        $bookmark = new Bookmark();
        $bookmark->setUser($user);

        $form = $this->createForm(new BookmarkType(), $bookmark);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($bookmark);
            $manager->flush();

            return $this->redirect($this->generateUrl('bookmarker_me'));
        }

        return array('form' => $form->createView());
    }

    /**
     * Display a tag cloud.
     *
     * @Template
     */
    public function cloudAction()
    {
        $repo = $this->getDoctrine()->getRepository('KingFooBookmarkerBundle:Tag');

        if ($user = $this->getUser()) {
            $tags = $repo->findCloud($user->getUsername());
        } else {
            $tags = $repo->findCloud();
        }

        return array('tags' => $tags);
    }
}
