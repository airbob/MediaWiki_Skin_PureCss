<?php
/**
 * PureCss: purecss.io skin for mediawiki 
 *                                     
 *
 * @Version 0.0.1
 * @Author @air_bob, <air.chenbo@gmail.com>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 */



if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinPureCss extends SkinTemplate {
	var $skinname = 'purecss', $stylename = 'purecss',
		$template = 'PureCssTemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ) {
		global $wgHandheldStyle;

		parent::setupSkinUserCss( $out );

		// Append to the default screen common & print styles...
		$out->addStyle( 'purecss/main.css', 'screen' );
		$out->addStyle( 'purecss/custom.css', 'screen' );
	}

}


class PureCssTemplate extends BaseTemplate {

	/**
	 * Template filter callback for MonoBook skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		$this->html( 'headelement' );
?>
<div id="layout" class="pure-g-r">

<!-- sidebar -->
<div id="menu" class="pure-u">
    <div class="pure-menu pure-menu-open">
        <a href="<?php echo $this->data['nav_urls']['mainpage']['href']?>"> <img src="<?php $this->text('stylepath') ?>/purecss/logo.png" alt="wiki logo" class="img-circle"/> </a>

<!-- rendering sidebar -->
<?php	$this->renderPortals( $this->data['sidebar'] );?>

<!-- TOOLS  -->
        	<a class="pure-menu-heading" >TOOLS</a>
		<ul>
			<li class="">
			<?php		foreach($this->getPersonalTools() as $key => $item) { ?>
			<?php echo $this->makeListItem($key, $item); ?>
			<?php		} ?>
			</li>
		</ul>

    </div>
</div><!-- end of the left (by default at least) column -->


<!-- Main Contents -->
<div id="main" class="pure-u-1">
    <div class="header">
    	<h1 id="firstHeading" class="firstHeading" lang="<?php
    		$this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getCode();
    		$this->html( 'pageLanguage' );
    	?>"><span dir="auto"><?php $this->html('title') ?></span></h1>
    </div>

    <div class="pure-g-r ">
    	<!-- start content -->
	<div class="pure-u-1 content">
    	<?php $this->html('bodytext') ?>
    	<?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
	</div>
    	<!-- end content -->
    	<?php if($this->data['dataAfterContent']) { $this->html ('dataAfterContent'); } ?>
    </div>
</div>

<?php
	$validFooterIcons = $this->getFooterIcons( "icononly" );
	$validFooterLinks = $this->getFooterLinks( "flat" ); // Additional footer links

	if ( count( $validFooterIcons ) + count( $validFooterLinks ) > 0 ) { ?>
<div class="legal pure-g-r">
<?php
		$footerEnd = '</div>';
	} else {
		$footerEnd = '';
	} ?>
    <div class="pure-u-1-3"></div>
    <div class="pure-u-1-6">
    <div class="l-box legal-logo">
        <a href="http://www.mediawiki.org/wiki/MediaWiki"> <img src="<?php $this->text('stylepath') ?>/purecss/mediawiki.png" height="30" width="65" alt="YUI logo" /> </a>
    </div>
    </div>

    <div class="pure-u-1-6">
    <div class="l-box legal-logo">
        <a href="http://yuilibrary.com/"> <img src="<?php $this->text('stylepath') ?>/purecss/yui.png" height="30" width="65" alt="YUI logo" /> </a>
    </div>
    </div>

    <div class="pure-u-1-6">
        <div class="l-box">
            <p class="legal-copyright">
                &copy; 2013 Company 
            </p>
        </div>
    </div>
    <div class="pure-u-1-6">
        <div class="l-box">
            <ul class="legal-links">
                <li>By <a href="https://twitter.com/air_bob">@air_bob</a></li>
            </ul>
        </div>
    </div>



<?php  
echo $footerEnd;
?>

</div>
<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
		wfRestoreWarnings();
	} // end of execute() method

	/*************************************************************************************************/

	/**
	 * @param $sidebar array
	 */
	protected function renderPortals( $sidebar ) {
		if ( !isset( $sidebar['SEARCH'] ) ) $sidebar['SEARCH'] = true;
		if ( !isset( $sidebar['TOOLBOX'] ) ) $sidebar['TOOLBOX'] = true;
		if ( !isset( $sidebar['LANGUAGES'] ) ) $sidebar['LANGUAGES'] = true;

		foreach( $sidebar as $boxName => $content ) {
			if ( $content === false )
				continue;

			if ( $boxName == 'SEARCH' ) {
				$this->searchBox();
			} elseif ( $boxName == 'TOOLBOX' ) {
				$this->toolbox();
			} elseif ( $boxName == 'LANGUAGES' ) {
				$this->languageBox();
			} else {
				$this->customBox( $boxName, $content );
			}
		}
	}

	function searchBox() {
		global $wgUseTwoButtonsSearchForm;
?>
        	<a class="pure-menu-heading" >SEARCH</a>
			<form action="<?php $this->text('wgScript') ?>" class="pure-form">
				<?php echo $this->makeSearchInput(array( "id" => "searchInput" )); ?>
				<?php if ($wgUseTwoButtonsSearchForm): ?>&#160;
				<?php echo $this->makeSearchButton("go", array( "id" => "mw-searchButton", "class" => "pure-button pure-button-primary search-button" ));
				else: ?>
				<a href="<?php $this->text('searchaction') ?>" rel="search"><?php $this->msg('powersearch-legend') ?></a><?php
				endif; ?>

			</form>
<?php
	}

	/**
	 * Prints the cactions bar.
	 * Shared between MonoBook and Modern
	 */
	function cactions() {
?>
        <a class="pure-menu-heading"><?php $this->msg('views') ?></a>
		<ul>
			<li><?php
				foreach($this->data['content_actions'] as $key => $tab) {
					echo '
				' . $this->makeListItem( $key, $tab );
				} ?>

			</li>
		</ul>
<?php
	}
	/*************************************************************************************************/
	function toolbox() {
?>
        <a class="pure-menu-heading"><?php $this->msg('toolbox') ?></a>
		<ul>
<?php
		foreach ( $this->getToolbox() as $key => $tbitem ) { ?>
				<?php echo $this->makeListItem($key, $tbitem); ?>

<?php
		}
		wfRunHooks( 'PureCssTemplateToolboxEnd', array( &$this ) );
		wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this, true ) );
?>
		</ul>
<?php
	}

	/*************************************************************************************************/
	function languageBox() {
		if( $this->data['language_urls'] ) {
?>
        <a class="pure-menu-heading"><?php $this->msg('otherlanguages') ?></a>
		<ul>
			<li class="">
			<?php		foreach($this->data['language_urls'] as $key => $langlink) { ?>
			<?php echo $this->makeListItem($key, $langlink); ?>
			<?php		} ?>
			</li>
		</ul>
<?php
		}
	}

	/*************************************************************************************************/
	/**
	 * @param $bar string
	 * @param $cont array|string
	 */
	function customBox( $bar, $cont ) {
		$tooltip = Linker::titleAttrib( "p-$bar" );
		if ( $tooltip !== false ) {
			$portletAttribs['title'] = $tooltip;
		}
?>

                <a class="pure-menu-heading"><?php $msg = wfMessage( $bar ); echo htmlspecialchars( $msg->exists() ? $msg->text() : $bar ); ?></a>
		<ul>
<?php   if ( is_array( $cont ) ) { ?>
<?php 			foreach($cont as $key => $val) { ?>
				<?php echo $this->makeListItem($key, $val); ?>

<?php			} ?>
<?php   } else {
			# allow raw HTML block to be defined by extensions
			print $cont;
		}
?>
		</ul> 
<!--	</div> -->
<?php
	}
} // end of class


