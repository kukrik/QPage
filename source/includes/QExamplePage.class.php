<?php
require_once('../../../../qcubed.inc.php');
require(__DOCROOT__ . __EXAMPLES__ . '/includes/examples.inc.php'); 
class QExamplePage extends QPage {
	// Here we set the default page header and footer
	protected $strPageFooter = '';
	protected $strPageHeader = '';
	
	protected function Form_Create() {
		parent::Form_Create();
		$this->PageTitle = QApplication::Translate(Examples::PageName()." - QCubed PHP 5 Development Framework - Examples");
		$this->Description = QApplication::Translate(
			Examples::PageName()." - QPage Example"
		);
		// We are actually reintroducing these, they are called in a normal QForm but were stripped out of QPage.
		$this->AddJavascriptFile('jquery/jquery.min.js');
		$this->AddJavascriptFile('qcubed.js');
//		$this->AddJavascriptFile('logger.js');
//		$this->AddJavascriptFile('event.js');
//		$this->AddJavascriptFile('post.js');
//		$this->AddJavascriptFile('control.js');
		$this->AddCSSFile('styles.css');
		$this->AddCSSFile('../php/examples/includes/examples.css');

		QApplication::ExecuteJavaScript("function ViewSource(intCategoryId, intExampleId, strFilename) {
				var fileNameSection = '';
				if (arguments.length == 3) {
					fileNameSection = '/' + strFilename;
				}
				var objWindow = window.open('".__EXAMPLES__."/view_source.php/' + intCategoryId + '/' + intExampleId + fileNameSection, 'ViewSource', 'menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes,width=1000,height=750,left=50,top=50');
				objWindow.focus();
			}");

		$this->strPageHeader = '<header><div class="breadcrumb">';
		if(!isset($mainPage)) {
			$this->strPageHeader .= '<span class="category-name">'.(Examples::GetCategoryId() + 1) . '. ' . Examples::$Categories[Examples::GetCategoryId()]['name'] .'</span> / ';
		}
		$this->strPageHeader .= '<strong class="page-name">'.Examples::PageName().'</strong></div>';
		
		if(!isset($mainPage)) {
			$this->strPageHeader .= '<nav class="page-links">'.Examples::PageLinks().'</nav>';
		}
			
		$this->strPageHeader .= '</header><section id="content">';

		$this->strPageFooter = '';

		if(!isset($mainPage)) {
			$this->strPageFooter .= '<button id="viewSource">View Source</button>';
		}

		$this->strPageFooter .= '</section><footer><div id="tagline"><a href="http://qcubed.github.com/" title="QCubed Homepage"><img id="logo" src="'.__VIRTUAL_DIRECTORY__ . __IMAGE_ASSETS__ . '/qcubed_logo_footer.png" alt="QCubed Framework" /> <span class="version">'.QCUBED_VERSION.'</span></a></div></footer>';
		$this->strPageFooter .= '<script type="text/javascript">
			var viewSource = document.getElementById(\'viewSource\');
			if (viewSource) {
				viewSource.onclick = function (){
					var fileNameSection = "",
						objWindow;
					if (arguments.length == 3) {
						fileNameSection = "/" + strFilename;
					}
					objWindow = window.open("'. __VIRTUAL_DIRECTORY__ . __EXAMPLES__ .'/view_source.php/'.Examples::GetCategoryId()."/".Examples::GetExampleId().'" + fileNameSection, "ViewSource", "menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes,width=1000,height=750,left=50,top=50");
					objWindow.focus();
					return false;
				};
			}			
			window.gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
			document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));

			try {
				window.pageTracker = _gat._getTracker("UA-7231795-1");
				pageTracker._trackPageview();
			} catch(err) {}
		</script>';
	}

	public function RenderBegin($blnDisplayOutput = true) {
		// Ensure that RenderBegin() has not yet been called
		switch ($this->intFormStatus) {
			case QFormBase::FormStatusUnrendered:
				break;
			case QFormBase::FormStatusRenderBegun:
			case QFormBase::FormStatusRenderEnded:
				throw new QCallerException('$this->RenderBegin() has already been called');
				break;
			default:
				throw new QCallerException('FormStatus is in an unknown status');
		}
		// Update FormStatus and Clear Included JS/CSS list
		$this->intFormStatus = QFormBase::FormStatusRenderBegun;
		
		
		$strToReturn = '';
		$strToReturn .= $this->HtmlHead();
		$strToReturn .= $this->HtmlBodyBegin();
		$strToReturn .= $this->HtmlFormBegin();
		// Header
		$strToReturn .= $this->strPageHeader;
		
		// Perhaps a strFormModifiers as an array to
		// allow controls to update other parts of the form, like enctype, onsubmit, etc.

		// Return or Display
		if ($blnDisplayOutput) {
			print($strToReturn);
			return null;
		} else
			return $strToReturn;
	}

	// Override the default QPage RenderEnd function to add page footer
	public function RenderEnd($blnDisplayOutput = true) {
		// Ensure that RenderEnd() has not yet been called
		switch ($this->intFormStatus) {
			case QFormBase::FormStatusUnrendered:
				throw new QCallerException('$this->RenderBegin() was never called');
			case QFormBase::FormStatusRenderBegun:
				break;
			case QFormBase::FormStatusRenderEnded:
				throw new QCallerException('$this->RenderEnd() has already been called');
				break;
			default:
				throw new QCallerException('FormStatus is in an unknown status');
		}

		
		$strToReturn = '';	
		
		
		// Footer
		$strToReturn .= $this->strPageFooter;
		$strToReturn .= $this->HtmlFormEnd();
		$strToReturn .= $this->WriteEndScripts();
		$strToReturn .= "\r\n</div></body></html>";

		// Update Form Status
		$this->intFormStatus = QFormBase::FormStatusRenderEnded;

		// Display or Return
		if ($blnDisplayOutput) {
			print($strToReturn);
			return null;
		} else
			return $strToReturn;
	}
}
?>