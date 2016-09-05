<?php

namespace Drupal\wishlist_template\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Builds the form to delete a wishlist template type.
 */
class WishlistTemplateTypeDeleteForm extends EntityDeleteForm {

  /**
   * The query factory to create entity queries.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $queryFactory;

  /**
   * Constructs a new WishlistTemplateTypeDeleteForm object.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $query_factory
   *   The entity query object.
   */
  public function __construct(QueryFactory $query_factory) {
    $this->queryFactory = $query_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $wishlist_template_count = $this->queryFactory->get('wishlist_template')
      ->condition('type', $this->entity->id())
      ->count()
      ->execute();
    if ($wishlist_template_count) {
      $caption = '<p>' . $this->formatPlural($wishlist_template_count, '%type is used by 1 wishlist template on your site. You can not remove this wishlist template type until you have removed all of the %type wishlist templates.', '%type is used by @count wishlist templates on your site. You may not remove %type until you have removed all of the %type wishlist templates.', ['%type' => $this->entity->label()]) . '</p>';
      $form['#title'] = $this->getQuestion();
      $form['description'] = ['#markup' => $caption];
      return $form;
    }

    return parent::buildForm($form, $form_state);
  }

}
