<?php

namespace Elec\ChicagoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\View\TwitterBootstrapView;

use Elec\ChicagoBundle\Entity\Alderman;
use Elec\ChicagoBundle\Form\AldermanType;
use Elec\ChicagoBundle\Form\AldermanFilterType;

/**
 * Alderman controller.
 *
 * @Route("/alderman")
 */
class AldermanController extends Controller
{
    /**
     * Lists all Alderman entities.
     *
     * @Route("/admin/alderman", name="admin_alderman")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        list($filterForm, $queryBuilder) = $this->filter();

        list($entities, $pagerHtml) = $this->paginator($queryBuilder);

        return array(
            'entities' => $entities,
            'pagerHtml' => $pagerHtml,
            'filterForm' => $filterForm->createView(),
        );
    }

    /**
    * Create filter form and process filter request.
    *
    */
    protected function filter()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $filterForm = $this->createForm(new AldermanFilterType());
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('ElecChicagoBundle:Alderman')->createQueryBuilder('e');

        // Reset filter
        if ($request->get('filter_action') == 'reset') {
            $session->remove('AldermanControllerFilter');
        }

        // Filter action
        if ($request->get('filter_action') == 'filter') {
            // Bind values from the request
            $filterForm->bind($request);

            if ($filterForm->isValid()) {
                // Build the query from the given form object
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
                // Save filter to session
                $filterData = $filterForm->getData();
                $session->set('AldermanControllerFilter', $filterData);
            }
        } else {
            // Get filter from session
            if ($session->has('AldermanControllerFilter')) {
                $filterData = $session->get('AldermanControllerFilter');
                $filterForm = $this->createForm(new AldermanFilterType(), $filterData);
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($filterForm, $queryBuilder);
            }
        }

        return array($filterForm, $queryBuilder);
    }

    /**
    * Get results from paginator and get paginator view.
    *
    */
    protected function paginator($queryBuilder)
    {
        // Paginator
        $adapter = new DoctrineORMAdapter($queryBuilder);
        $pagerfanta = new Pagerfanta($adapter);
        $currentPage = $this->getRequest()->get('page', 1);
        $pagerfanta->setCurrentPage($currentPage);
        $entities = $pagerfanta->getCurrentPageResults();

        // Paginator - route generator
        $me = $this;
        $routeGenerator = function($page) use ($me)
        {
            return $me->generateUrl('admin_alderman', array('page' => $page));
        };

        // Paginator - view
        $translator = $this->get('translator');
        $view = new TwitterBootstrapView();
        $pagerHtml = $view->render($pagerfanta, $routeGenerator, array(
            'proximity' => 3,
            'prev_message' => $translator->trans('views.index.pagprev', array(), 'JordiLlonchCrudGeneratorBundle'),
            'next_message' => $translator->trans('views.index.pagnext', array(), 'JordiLlonchCrudGeneratorBundle'),
        ));

        return array($entities, $pagerHtml);
    }

    /**
     * Creates a new Alderman entity.
     *
     * @Route("/admin/alderman", name="admin_alderman_create")
     * @Method("POST")
     * @Template("ElecChicagoBundle:Alderman:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Alderman();
        $form = $this->createForm(new AldermanType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.create.success');

            return $this->redirect($this->generateUrl('admin_alderman_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Alderman entity.
     *
     * @Route("/admin/alderman/new", name="admin_alderman_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Alderman();
        $form   = $this->createForm(new AldermanType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Alderman entity.
     *
     * @Route("/admin/alderman/{id}", name="admin_alderman_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElecChicagoBundle:Alderman')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alderman entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Alderman entity.
     *
     * @Route("/admin/alderman/{id}/edit", name="admin_alderman_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElecChicagoBundle:Alderman')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alderman entity.');
        }

        $editForm = $this->createForm(new AldermanType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Alderman entity.
     *
     * @Route("/admin/alderman/{id}", name="admin_alderman_update")
     * @Method("PUT")
     * @Template("ElecChicagoBundle:Alderman:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ElecChicagoBundle:Alderman')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Alderman entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AldermanType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.update.success');

            return $this->redirect($this->generateUrl('admin_alderman_edit', array('id' => $id)));
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.update.error');
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Alderman entity.
     *
     * @Route("/admin/alderman/{id}", name="admin_alderman_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ElecChicagoBundle:Alderman')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Alderman entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'flash.delete.success');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'flash.delete.error');
        }

        return $this->redirect($this->generateUrl('admin_alderman'));
    }

    /**
     * Creates a form to delete a Alderman entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /** FRONT-END VIEWS 
     *
     *
     *
     *
     */
     
    /**
     * Display all info about an alderman for a given ward
     *
     * @Route("/{ward}", name="alderman")
     * @Method("GET")
     * @Template()
     */
    public function getAldermanAction($ward)
    {
        $em = $this->getDoctrine()->getManager();

//        $thisWard = $em->getRepository('ElecChicagoBundle:Ward')->findOneById($ward);
//        $wardId = $thisWard->getId();

        $alderman = $em->getRepository('ElecChicagoBundle:Alderman')->findOneByWard($ward);

        if (!$alderman) {
            throw $this->createNotFoundException('Unable to find Ward entity.');
        }
   
        return $this->render('ElecChicagoBundle:Alderman:alderman.html.twig', array('alderman' => $alderman));
    }  
}
