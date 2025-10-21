<h1 align="center">
	<a href="http://aseqbase.ir" target="_blank">
		<img alt="MiMFa aseqbase" width="20%" src="https://aseqbase.mimfa.net/asset/logo/logo.svg"/>
		<br>
		MiMFa <img alt="MiMFa aseqbase" height="25px" src="https://aseqbase.mimfa.net/asset/logo/brand-logo.svg"/>
	</a>
	<br><sub><a href="http://aseqbase.ir" target="_blank">a seq</a>uence-<a href="http://aseqbase.ir" target="_blank">base</a>d web development framework</sub>
</h1>

<p><a href="http://aseqbase.ir" target="_blank">MiMFa aseqbase</a> is a unique framework for web development called "aseqbase" (a sequence-based framework) has been developed to implement safe, flexible, fast, and strong pure websites based on that, since 2018 so far.</p>

<h3>Demonstrations</h3>
	<p>There is a list of multiple websites, developed by this framework:</p>
	<table>
		<tr><th>NAME</th><th>DESCRIPTION</th><th>VISIT</th></tr>
		<tr><td>MiMFa</td><td>A Technology Provider</td><td><a href="http://mimfa.net" target="_blank">&#128279</a></td></tr>
		<tr><td>aseqbase</td><td>The original website</td><td><a href="http://aseqbase.ir" target="_blank">&#128279</a></td></tr>
		<tr><td>IRMS</td><td>An Integrated Resources Management System stands special for an aseqbase website...</td><td><a href="http://ingma.mimfa.net" target="_blank">&#128279</a></td>
		<tr><td>DataLab</td><td>An Integrated Software for Data Scientists and Analysts!</td><td><a href="http://datalab.mimfa.net" target="_blank">&#128279</a></td></tr>
		<tr><td>Scraper</td><td>An Integrated Software for Automatic Data Extraction and Collecting!</td><td><a href="http://scraper.mimfa.net" target="_blank">&#128279</a></td></tr>
		<tr><td>PubkyFace</td><td>10,000 unique collectible NFT Characters with proof of ownership stored on the Polygon blockchain</td><td><a href="http://pf.mimfa.net" target="_blank">&#128279</a></td></tr>
	</table>

<h3>Requirements</h3>
	<p>This version is available for:</p>
	<h4>Operating System Options:</h4>
		<table>
			<tr><th>PLATFORM</th><th>VER</th></tr>
			<tr><td>Linux</td><td>32–bit/64–bit</td></tr>
			<tr><td>Microsoft Windows</td><td>32–bit/64–bit</td></tr>
		</table>
	<h4>Programming Language Options:</h4>
		<table>
			<tr><th>ENGINE</th><th>VER</th></tr>
			<tr><td>PHP</td><td>8.2 +</td></tr>
		</table>
	<h4>Web Server Options:</h4>
		<table>
			<tr><th>SERVER</th><th>VER</th></tr>
			<tr><td>Apache</td><td>2.x</td></tr>
			<tr><td>Nginx</td><td>1.x</td></tr>
			<tr><td>Microsoft IIS</td><td>7</td></tr>
		</table>
	<h4>DataBase Options:</h4>
		<table>
			<tr><th>DATABASE</th><th>VER</th></tr>
			<tr><td>MySQL</td><td>5.1 + (5.7 + preferred)</td></tr>
			<tr><td>SQL Server</td><td>10.50.1600.1 +</td></tr>
			<tr><td>PostgreSQL</td><td>8.3.18 +</td></tr>
		</table>

<h3>Managements</h3>
<h4>Installing</h4>

  1. Install all requirements mentioned above
  2. Follow one of these options:
		* Open a terminal in the destination directory (for example, `D:\MyWebsite\`) of the website, then install the framework by:
			``` bash
			> composer create-project aseqbase/aseqbase ./
			```
		* Prompts below to create a manageable framework (update, uninstall, etc.):
			``` bash
			> composer require aseqbase/aseqbase
			> cd vendor/aseqbase/aseqbase
			> composer dev:install
			```
  3. Put the destination directory of your website on the appeared step (for example, `D:\MyWebsite\`)
		``` bash
		Destination Directory [D:\MyWebsite\]: D:\MyWebsite\
		```
  4. Follow the steps to finish the installation of sources, database, etc.
  * [optional] Create an optional file named `global.php` in the root directory with the following script:
	  ``` php
	  <?php
		$BASE = '[the-parent-directory-name]'; 		// (Optional) The parent directory you want to inherit all properties except what you changed
		$ASEQ = '[the-carrent-directory-name]'; 	// (Optional) The current subdomain sequence, or leave null if this file is in the root directory
		$SEQUENCES_PATCH = [];		// (Optional) An array to apply your custom changes in \_::$Sequences
									// newdirectory, newaseq; // Add new directory to the \_::$Sequences
									// directory, newaseq; // Update directory in the \_::$Sequences
									// directory, null; // Remove thw directory from the \_::$Sequences
	  ?>
	  ```
  5. Enjoy...
<h4>Using</h4>

  1. Do one of the following options:
	  	* Visit its special URL (for example, `http://[my-domain-name].com` or `http://[my-subdomain-name].[my-domain-name].com`)
		* On the local server:
			1. Use the following command on the root directory
				``` bash
				> composer start
		  		```
		  	2. Visit the URL `localhost:8000` on the local browser
  2. Enjoy...

<h4>Updating</h4>

  1. Keep your framework updated using
		``` bash
		> composer aseqbase:update
		```
		or
		``` bash
  		> cd vendor/aseqbase/aseqbase
		> composer dev:update
		```
  2. Follow the steps to finish the update of sources, database, etc.
  3. Enjoy...

<h4>Uninstalling</h4>

  1. Uninstall the framework and the constructed database using
		``` bash
		> composer aseqbase:unistall
		```
		or
		``` bash
  		> cd vendor/aseqbase/aseqbase
		> composer dev:unistall
		```
  2. Follow the steps to finish the uninstallation of sources, database, etc.
  3. Enjoy...

<h4>Creating</h4>

  1. Create a new file by a predefined template name (for example, global, config, back, router, front, user, info, etc.) using
		``` bash
		> composer aseqbase:create [predefined-template-name]
		```
		or
		``` bash
  		> cd vendor/aseqbase/aseqbase
		> composer dev:create [predefined-template-name]
		```
  2. Follow the steps to finish creating the file.
  3. Enjoy...

<h3>Contributions</h3>
	<p>Contributions can take the form of new components or features, changes to existing features, tests, documentation (such as developer guides, user guides, examples, or specifications), bug fixes, optimizations, or just good suggestions.</p>

<h3>License</h3>
	<p>aseqbase Core is released under the terms of the GNU General Public License.</p>

<h3>Links</h3>
	<ul>
		<li>To download the latest release, click <a href="http://aseqbase.mimfa.net/download" target="_blank">here &#128279</a>.</li>
		<li>To watch demonstration and tutorial videos, click <a href="http://media.mimfa.net" target="_blank">here &#128279</a>.</li>
		<li>To read about documentations, click <a href="https://github.com/aseqbase/aseqbase/wiki" target="_blank">here &#128279</a>.</li>
		<li>To learn about issues, click <a href="https://github.com/aseqbase/aseqbase/issues" target="_blank">here &#128279</a>.</li>
		<li>To suggest documentation improvements, click <a href="http://chat.mimfa.net" target="_blank">here &#128279</a>.</li>
	</ul>
