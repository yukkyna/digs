<?php

namespace Digs\PhotoBundle\Services;

use Digs\ImageConverterBundle\Services\ImageMagickManager;
use Digs\PhotoBundle\Entity\Photo;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Paginator;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Photo controller.
 *
 */
class PhotoService
{
	private $templating;
	private $em;
	private $formFactory;
	private $paginator;
	private $router;
	private $converter;
	
	public function __construct(EngineInterface $templating, EntityManager $em,
		FormFactory $formFactory, Paginator $paginator, RouterInterface $router, ImageMagickManager $converter)
	{
		$this->templating = $templating;
		$this->em = $em;
		$this->formFactory = $formFactory;
		$this->paginator = $paginator;
		$this->router = $router;
		$this->converter = $converter;
	}

    /**
     * Lists all Photo entities.
     *
     */
    public function indexAction(Query $query, $page, $limit, $showRoute, $showPrefix, $actionRoute, $reloadRoute, $showThumbnailRoute)
    {
        $entities = $this->paginator->paginate($query, $page, $limit);
		$uploadForm = $this->createCreateForm($actionRoute);

        return $this->templating->renderResponse('DigsPhotoBundle:Photo:index.html.twig', array(
            'entities' => $entities,
			'prefix' => $showPrefix,
			'upload_form' => $uploadForm->createView(),
			'reloadRoute' => $reloadRoute,
			'showRoute'   => $showRoute,
			'showThumbnailRoute' => $showThumbnailRoute,
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
    * Creates a form to create a Photo entity.
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
     * Displays a form to create a new Photo entity.
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
					
					$dir = $uploadDir . $typeId . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR;
					
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
					$file->move($dir, $newname . '.original');
					
					$ret = $this->converter->convert('-quality 100 ' . $dir . $newname . '.original ' . $dir . $newname . '.jpg');
					if ($ret == 0)
					{
						$this->converter->convert('-quality 60 -resize 300x300 ' . $dir . $newname . '.original ' . $dir . 't_' . $newname . '.jpg');
						$entity = new Photo();
						$entity->setTitle($file->getClientOriginalName());
						$entity->setFile($newname);
						$entity->setStatus(1);
						$entity->setTypeId($typeId);
						
						$this->em->persist($entity);
						$this->em->flush();
					}
					else {
						$form->addError(new FormError('対応していない画像です。'));
					}
				}
			}
		}
		catch (FileException $e)
		{
			$form->addError(new FormError('ファイルサイズが大きすぎます。'));
		}

		return $this->templating->renderResponse('DigsPhotoBundle:Photo:new.html.twig', array(
			'upload_form' => $form->createView()
		));
    }

	/**
     * Finds and displays a Photo entity.
	 * 事前にユーザーチェックすること
     *
     */
    public function showAction(Request $request, $uploadDir, $prefix, $file)
    {
        $entity = $this->em->getRepository('DigsPhotoBundle:Photo')->findMemberPhoto($prefix, $file);
        if (!$entity) {
            throw new NotFoundHttpException('Unable to find Photo entity.');
        }
//
//		$response = new Response();
//		$response->setLastModified($entity->getCreatedAt());
//		if ($response->isNotModified($request))
//		{
//			return $response;
//		}

		$path = $uploadDir . $prefix . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . $entity->getFile() . '.jpg';
		ob_start();
			readfile($path);
			$image = ob_get_contents();
		ob_end_clean();
		
		$response = new Response($image, 200, array(
			'Content-Type'   => 'image/jpeg',
			'Content-Length' => filesize($path),
		));
		$response->setLastModified($entity->getCreatedAt());
        return $response;
    }

    public function showThumbnailAction(Request $request, $uploadDir, $prefix, $file)
    {
        $entity = $this->em->getRepository('DigsPhotoBundle:Photo')->findMemberPhoto($prefix, $file);
        if (!$entity) {
            throw new NotFoundHttpException('Unable to find Photo entity.');
        }
//
//		$response = new Response();
//		$response->setLastModified($entity->getCreatedAt());
//		if ($response->isNotModified($request))
//		{
//			return $response;
//		}

		$path = $uploadDir . $prefix . DIRECTORY_SEPARATOR . 'photo' . DIRECTORY_SEPARATOR . 't_' .$entity->getFile() . '.jpg';
		ob_start();
			readfile($path);
			$image = ob_get_contents();
		ob_end_clean();

		$response = new Response($image, 200, array(
			'Content-Type'   => 'image/jpeg',
			'Content-Length' => filesize($path),
		));
		$response->setLastModified($entity->getCreatedAt());
        return $response;
    }
}
