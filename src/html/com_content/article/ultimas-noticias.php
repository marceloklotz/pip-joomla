<?php
/**
 * @package		
 * @subpackage	
 * @copyright	
 * @license		
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);

$canEdit = $this->item->params->get('access-edit');
$user    = JFactory::getUser();

if($this->item->catid <= 2)
	$params->set('show_category', 0);
?>

<div class="item-page<?php echo $this->pageclass_sfx?>">
<?php if ( @$this->item->xreference != '') : ?>
	<span class="documentCategory"><?php echo $this->escape($this->item->xreference); ?></span>
<?php elseif ($this->params->get('show_page_heading')) : ?>
	<span class="documentCategory"><?php echo $this->escape($this->params->get('page_heading')); ?></span>
<?php elseif ($params->get('show_category')) : ?>
	<span class="documentCategory">
	<?php 	$title = $this->escape($this->item->category_title);
	$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)).'">'.$title.'</a>';?>
	<?php if ($params->get('link_category') and $this->item->catslug) : ?>
		<?php echo $url; ?>
	<?php else : ?>
		<?php echo $title; ?>
	<?php endif; ?>
	</span>
<?php endif; ?>

<?php if ($params->get('show_title')) : ?>
	<h1 class="secondaryHeading">
	<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
		<a href="<?php echo $this->item->readmore_link; ?>">
		<?php echo $this->escape($this->item->title); ?></a>
	<?php else : ?>
		<?php echo $this->escape($this->item->title); ?>
	<?php endif; ?>
	</h1>
<?php endif; ?>

<?php if ($canEdit ||  $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<ul class="actions">
	<?php if (!$this->print) : ?>
		<?php if ($canEdit) : ?>
			<li class="edit-icon">
			<?php echo JHtml::_('icon.edit', $this->item, $params); ?>
			</li>
		<?php endif; ?>
	<?php else : ?>
		<li>
		<?php echo JHtml::_('icon.print_screen',  $this->item, $params); ?>
		</li>
	<?php endif; ?>
	</ul>
<?php endif; ?>

<?php  if (!$params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>

<?php echo $this->item->event->beforeDisplayContent; ?>

<?php if($this->item->fulltext != null && $this->item->fulltext!=''): ?>
<div class="subtitle">
	<?php echo $this->item->introtext; ?>
</div>
<?php endif; ?>

<div class="content-header-options-1 row-fluid">
	<div class="documentByLine span7">
		<ul>
		<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
			<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author;
					$author = '<strong>'.$author.'</strong>';
			?>
			<?php if (!empty($this->item->contactid) && $params->get('link_author') == true): ?>
			<?php
				$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
				$menu = JFactory::getApplication()->getMenu();
				$item = $menu->getItems('link', $needle, true);
				$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
			?>
				<li class="documentAuthor"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author)); ?></li>
			<?php else: ?>
				<li class="documentAuthor"><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?></li>
			<?php endif; ?>
		<?php endif; ?>
		
		<?php if ($params->get('show_create_date')) : ?>
			<li class="documentCreated">
				<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2'))); ?>			
			</li>
		<?php endif; ?>	

		<?php if ($params->get('show_publish_date')) : ?>
			<li class="documentPublished">
				<?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC2'))); ?>
			</li>
		<?php endif; ?>	

		<?php if ($params->get('show_modify_date')) : ?>
			<li class="documentModified">
				<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
			</li>
		<?php endif; ?>	
		
		<?php if ($params->get('show_hits')) : ?>
			<li class="documentHits">
				<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
			</li>
		<?php endif; ?>		
		</ul>
	</div>
	<?php $modules = JModuleHelper::getModules( 'com_content-article-btns-social' );
		if(count($modules)): ?>
		<div class="btns-social-like span5 hide">
			<?php foreach($modules as $module): ?>
				<?php $html = JModuleHelper::renderModule($module); ?>
				<?php $html = str_replace('{SITE}', JURI::root(), $html); ?>
				<?php echo $html; ?>
			<?php endforeach; ?>
		</div>		
	<?php	
		endif;
	 ?>	
</div>
<!-- fim .content-header-options-1 -->


<?php if (isset ($this->item->toc)) : ?>
	<?php echo $this->item->toc; ?>
<?php endif; ?>

<?php if (isset($urls) AND ($params->get('urls_position')=='0')): ?>
	<?php if($urls->urla || $urls->urlb || $urls->urlc): ?>
	<blockquote>
		<p><?php echo JText::_('TPL_PIP_LINKS_RELACIONADOS'); ?>:</p>
		<?php echo $this->loadTemplate('links'); ?>
	</blockquote>
	<?php endif; ?>
<?php endif; ?>

<?php if ($params->get('access-view')):?>
<?php
if (!empty($this->item->pagination) AND $this->item->pagination AND !$this->item->paginationposition AND !$this->item->paginationrelative):
	echo $this->item->pagination;
 endif;
?>

<?php  TemplateContentArticleHelper::displayFulltextImage($images, $params); ?>

<?php if($this->item->fulltext != null && $this->item->fulltext != ''): ?>
	<?php echo $this->item->fulltext; ?>
<?php else: ?>
	<?php echo $this->item->text; ?>
<?php endif; ?>
<?php
if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND!$this->item->paginationrelative):
	 echo $this->item->pagination;?>
<?php endif; ?>

<?php //optional teaser intro text for guests ?>
<?php elseif ($params->get('show_noauth') == true and  $user->get('guest') ) : ?>
	<div class="description">
	<?php echo $this->item->introtext; ?>
	</div>
	<?php //Optional link to let them register to see the whole article. ?>
	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
		$link1 = JRoute::_('index.php?option=com_users&view=login');
		$link = new JURI($link1);?>
		<p class="readmore">
		<a href="<?php echo $link; ?>">
		<?php $attribs = json_decode($this->item->attribs);  ?>
		<?php
		if ($attribs->alternative_readmore == null) :
			echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
		elseif ($readmore = $this->item->alternative_readmore) :
			echo $readmore;
			if ($params->get('show_readmore_title', 0) != 0) :
			    echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif;
		elseif ($params->get('show_readmore_title', 0) == 0) :
			echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
		else :
			echo JText::_('COM_CONTENT_READ_MORE');
			echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
		endif; ?></a>
		</p>
	<?php endif; ?>
<?php endif; ?>
<?php
if (!empty($this->item->pagination) AND $this->item->pagination AND $this->item->paginationposition AND $this->item->paginationrelative):
	 echo $this->item->pagination;?>
<?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?>
</div>
<?php
$categories = TemplateContentArticleHelper::getParentCategoriesByRoute($this->item->parent_route);
$showBelowContent = TemplateContentArticleHelper::showBelowContent($categories, $this->item);
if (count($showBelowContent) > 0): ?>
<div class="below-content">
	<?php if (in_array('categories', $showBelowContent)): ?>
	<div class="line">
		<?php echo JText::_('TPL_PIP_REGISTRADO_EM'); ?>:
		<?php TemplateContentArticleHelper::displayCategoryLinks($categories, $this->item); ?>
	</div>
	<?php endif; ?>
	<?php if (in_array('metakeys', $showBelowContent)): ?>
	<div class="line">
		<?php echo JText::_('TPL_PIP_ASSUNTOS'); ?>:
		<?php TemplateContentArticleHelper::displayMetakeyLinks( $this->item->metakey ); ?>		
	</div>
	<?php endif; ?>

	<?php if (isset($urls) AND $params->get('urls_position')!='0' ): ?>
		<?php if($urls->urla || $urls->urlb || $urls->urlc): ?>
		<div class="line">
			<h3><?php echo JText::_('TPL_PIP_LINKS_RELACIONADOS'); ?>: </h3>
			<?php echo $this->loadTemplate('links'); ?>
		</div>
		<?php endif; ?>
	<?php endif; ?>

</div>
<?php endif; ?>