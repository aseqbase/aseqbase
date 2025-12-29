<h1 align="center">
	<a href="http://aseqbase.ir" target="_blank">
		<img alt="MiMFa aseqbase" width="20%" src="https://aseqbase.mimfa.net/asset/logo/logo.svg"/>
		<br>
		MiMFa <img alt="MiMFa aseqbase" height="25px" src="https://aseqbase.mimfa.net/asset/logo/brand-logo.svg"/>
	</a>
	<br><sub><a href="http://aseqbase.ir" target="_blank">a seq</a>uence-<a href="http://aseqbase.ir" target="_blank">base</a>d web development framework</sub>
</h1>

<p><a href="http://aseqbase.ir" target="_blank">MiMFa aseqbase</a> is a sequence-based web development framework designed by MiMFa to simplify the creation of modular, scalable, fast, safe, and accessible websites. Whether you're building  hierarchical platforms, subdomains, single-page applications, and so on. aseqbase provides the structure and tools to do it efficiently.

aseqbase is built around the concept of **sequential web architecture** where each component, page, or subdomain follows a logical, layered sequence for deployment and accessibility. This makes it ideal for:
	- Multi-subdomain websites
	- Single-page applications
	- Custom CMS setups
	- Educational or media-rich platforms
	- ...

Explore the ecosystem:
	- [Main site](http://aseqbase.ir)
	- [Developer portal](https://dev.aseqbase.ir)
	- [Media hub](https://media.aseqbase.ir)
	- [CV builder](https://cv.aseqbase.ir)
	- [Image tools](https://i.aseqbase.ir)

## Repositories

| Repo         | Description                                                                 |
|--------------|-----------------------------------------------------------------------------|
| `aseqbase`   | Core framework for sequence-based web development                           |
| `sequence`   | Tools to create new subdomains with full accessibility and equipment        |
| `single`     | Setup for single-page websites or subdomains                                |
| `administrator` | Default CMS tailored for aseqbase websites                              |
| ...          |                                                                            |

<h2>Demonstrations</h2>
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

<h2>Requirements</h2>
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

<h2>Managements</h2>
<h4>Installing</h4>

  1. Install all requirements mentioned above
  2. Open a terminal in the destination directory (for example, `D:\MyWebsite\`) of the website, then follow one of these options:
		* Prompts below to create a manageable framework (update, uninstall, etc.):
			``` bash
			> composer require aseqbase/aseqbase
			> cd vendor/aseqbase/aseqbase
			vendor/aseqbase/aseqbase> composer dev:install
			```
		* Install the framework by:
			``` bash
			> composer create-project aseqbase/aseqbase ./
			```
  3. Put the destination directory of your website on the appeared step (for example, `D:\MyWebsite\`)
		``` bash
		Destination Directory [D:\MyWebsite\]: D:\MyWebsite\
		```
  4. Follow the steps to finish the installation of sources, database, etc.
  5. [optional] Create an optional file named `global.php` in the root directory to change your-parent-directory-name (from the `.aseq`) using:
		``` bash
		> composer aseqbase:create global --base "your-parent-directory-name" -f
		```
		or
		``` bash
		vendor/aseqbase/aseqbase> composer dev:create global --base "your-parent-directory-name" -f
		```
		**Note**: Do not forget to replace "your-parent-directory-name" with your item (default `.aseq`). 
  6. Enjoy...
<h4>Using</h4>

  1. Do one of the following options:
	  	* Visit its special URL (for example, `http://[my-domain-name].com` or `http://[my-subdomain-name].[my-domain-name].com`)
		* On the local server:
			1. Use the following command on the root directory
				``` bash
				> composer start
		  		```
		  	2. Visit the URL `localhost:8000` (for default) on the local browser
  2. Enjoy...

<h4>Updating</h4>

  1. Keep your framework updated using:
		``` bash
		> composer aseqbase:update
		```
		or
		``` bash
  		> cd vendor/aseqbase/aseqbase
		vendor/aseqbase/aseqbase> composer dev:update
		```
  2. Follow the steps to finish the update of sources, database, etc.
  3. Enjoy...

<h4>Uninstalling</h4>

  1. Uninstall the framework and the constructed database using:
		``` bash
		> composer aseqbase:unistall
		```
		or
		``` bash
  		> cd vendor/aseqbase/aseqbase
		vendor/aseqbase/aseqbase> composer dev:unistall
		```
  2. Follow the steps to finish the uninstallation of sources, database, etc.
  3. Enjoy...

<h4>Creating</h4>

  1. Create a new file by a predefined template name (for example, global, config, back, router, front, user, info, etc.) using:
		``` bash
		> composer aseqbase:create [predefined-template-name]
		```
		or
		``` bash
  		> cd vendor/aseqbase/aseqbase
		vendor/aseqbase/aseqbase> composer dev:create [predefined-template-name]
		```
  2. Follow the steps to finish creating the file.
  3. Enjoy...

<h2>Contributions</h2>
	<p>Contributions can take the form of new components or features, changes to existing features, tests, documentation (such as developer guides, user guides, examples, or specifications), bug fixes, optimizations, or just good suggestions.</p>


We welcome contributions! To get involved:
	1. Fork the repository
	2. Create a feature branch
	3. Submit a pull request with clear documentation


Detailed guides and examples are available at [dev.aseqbase.ir](https://dev.aseqbase.ir). You’ll find:
	- Setup walkthroughs
	- CMS customization
	- Subdomain management
	- Accessibility best practices


To start contributing to aseqbase:

* **Technologies Used**:
	- PHP (Core language)
	- Modular architecture
	- Hierarchy automation

* **Clone the core framework**:
   ```bash
   git clone https://github.com/aseqbase/aseqbase.git
   ```

* **Choose your deployment type**:
   - For multi-subdomain: contributing to the `sequence` repo
   - For single-page: contributing to the `single` repo
   - For CMS: contributing to the `administrator` repo
   - ...


<h2>License</h2>
	<p>aseqbase Core is released under the terms of the GNU General Public License.</p>

<h2>Links</h2>
	<ul>
		<li>To download the latest release, click <a href="http://aseqbase.mimfa.net/download" target="_blank">here &#128279</a>.</li>
		<li>To watch demonstration and tutorial videos, click <a href="http://media.mimfa.net" target="_blank">here &#128279</a>.</li>
		<li>To read about documentations, click <a href="https://github.com/aseqbase/aseqbase/wiki" target="_blank">here &#128279</a>.</li>
		<li>To learn about issues, click <a href="https://github.com/aseqbase/aseqbase/issues" target="_blank">here &#128279</a>.</li>
		<li>To suggest documentation improvements, click <a href="http://chat.mimfa.net" target="_blank">here &#128279</a>.</li>
	</ul>
