<?php

namespace Digs\FileBundle\Services;

use Digs\FileBundle\Entity\File;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * File controller.
 *
 */
class FileService
{
	private $templating;
	private $em;
	private $formFactory;
	private $paginator;
	private $router;
	
	public function __construct(EngineInterface $templating, EntityManager $em, FormFactory $formFactory, Paginator $paginator, RouterInterface $router)
	{
		$this->templating = $templating;
		$this->em = $em;
		$this->formFactory = $formFactory;
		$this->paginator = $paginator;
		$this->router = $router;
	}

	/**
     * Lists all File entities.
     *
     */
    public function indexAction(Query $query, $page, $limit, $showRoute, $showPrefix, $actionRoute, $reloadRoute)
    {
        $entities = $this->paginator->paginate($query, $page, $limit);
		$uploadForm = $this->createCreateForm($actionRoute);

        return $this->templating->renderResponse('DigsFileBundle:File:index.html.twig', array(
            'entities'    => $entities,
			'prefix'      => $showPrefix,
			'upload_form' => $uploadForm->createView(),
			'reloadRoute' => $reloadRoute,
			'showRoute'   => $showRoute,
        ));
    }

	/**
	 * Symfony\Bundle\FrameworkBundle\Controller
	 */ 
    private function createFormBuilder($data = null, array $options = array())
    {
        return $this->formFactory->createBuilder('form', $data, $options);
    }

    private function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }
	
	/**
    * Creates a form to create a File entity.
    *
    * @return Form The form
    */
    private function createCreateForm($actionRoute)
    {
		return $this->createFormBuilder()
			->setAction($this->generateUrl($actionRoute))
			->setMethod('POST')
            ->add('file', 'file')
            ->getForm();
    }

    /**
     * Displays a form to create a new File entity.
     *
     */
    public function newAction(Request $request, $actionRoute, $uploadDir, $typeId)
    {
		$form = $this->createCreateForm($actionRoute);
		try
		{
			if ($request->isMethod('POST'))
			{
				$form->handleRequest($request);
				if ($form->isValid()) {
					
					$dir = $uploadDir . $typeId . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR;
					
					// FIXME リトライしてもファイル名が重複し続けた場合
					$newname = '';
					for ($i = 0; $i < 32; $i ++)
					{
						$newname = md5(uniqid(null, true));
						if (!file_exists($dir . $newname))
						{
							break;
						}
					}

					$file = $form['file']->getData();
					$file->move($dir, $newname);
					
					$entity = new File();
					$entity->setTitle($file->getClientOriginalName());
					$entity->setFile($newname);
					$entity->setTypeId($typeId);
					$entity->setTextData("");

					$this->em->persist($entity);
					$this->em->flush();
				}
			}
		}
		catch (FileException $e)
		{
			$form->addError(new FormError('ファイルサイズが大きすぎます。'));
		}

        return $this->templating->renderResponse('DigsFileBundle:File:new.html.twig', array(
            'upload_form'   => $form->createView(),
        ));
    }

	/**
     * Finds and displays a File entity.
     *
     */
    public function showAction($uploadDir, $prefix, $file)
    {
		// ユーザーチェックする
        $entity = $this->em->getRepository('DigsFileBundle:File')->findMemberFile($prefix, $file);
        if (!$entity) {
            throw new NotFoundHttpException('Unable to find entity.');
        }

		$response = new Response();
//		$response->setLastModified($entity->getCreatedAt()->);
//		if ($response->isNotModified($request))
//		{
//			return $response;
//		}
		$response->headers->add(array(
			'Content-Type'   => 'application/octet-stream',
//			'Content-Length' => filesize($path),
			'X-Sendfile'     => $uploadDir . $prefix . DIRECTORY_SEPARATOR . 'file' . DIRECTORY_SEPARATOR . $entity->getFile(),
		));
		$response->setStatusCode(200);
//		$response =  new Response($image, 200);
		$response->setLastModified($entity->getCreatedAt());
        return $response;

//		$em = $this->getDoctrine()->getManager();
//
//        $entity = $em->getRepository('DigsFileBundle:File')->find($id);
//
//        if (!$entity) {
//            throw $this->createNotFoundException('Unable to find File entity.');
//        }
//
//        $deleteForm = $this->createDeleteForm($id);
//
//        return $this->render('DigsFileBundle:File:show.html.twig', array(
//            'entity'      => $entity,
//            'delete_form' => $deleteForm->createView(),        ));
    }
}
