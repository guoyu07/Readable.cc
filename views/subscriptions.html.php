<?php require 'header.html.php' ?>

<div class="page-header">
	<h1><?php echo $this->get('pageTitle') ?></h1>
</div>

<?php if ( $this->get('success') ): ?>
<div class="alert alert-success">
	<?php echo $this->get('success'); ?>
</div>
<?php endif ?>

<?php if ( $this->get('error') ): ?>
<div class="alert alert-error">
	<?php echo $this->get('error', false); ?>
</div>
<?php endif ?>

<div class="jump">
	<p>
		Jump to:
	</p>

	<ul>
		<li><a href="#feed-subscribe">Subscribe to feed</a></li>
		<li><a href="#folders">Manage folders</a></li>
		<li><a href="#feed-import-export">Import &amp; export feeds</a></il>
	</ul>
</div>

<?php $folders = $this->get('folders') ?>

<?php if ( $feeds = $this->get('feeds') ): ?>
<h3>Subscriptions</h3>

<table id="subscriptions" class="table table-bordered table-striped table-hover table-list">
	<tbody>
		<tr>
			<th>Subscription</th>
			<th>Folder</th>
			<th>Action</th>
		</tr>
		<?php foreach ( $feeds as $feed ): ?>
		<tr>
			<td>
				<a href="<?php echo $this->app->getSingleton('helper')->getFeedLink($feed->id, $feed->title) ?>"><?php echo $feed->title ?></a>
				<span>
					<a href="<?php echo $feed->link ?>" title="Visit the website at <?php echo parse_url($feed->link, PHP_URL_HOST) ?>"><i class="entypo link"></i></a>
					<a href="<?php echo $feed->url  ?>" title="View the feed at <?php echo parse_url($feed->url,  PHP_URL_HOST) ?>"><i class="entypo rss"></i></a>
					<small>
						&nbsp;
						<em><?php echo $feed->last_fetched_at ? 'Last fetched on ' . date('F j, Y', $feed->last_fetched_at) : 'Never successfully fetched' ?></em>
						&nbsp;
					</small>
				</span>
			</td>
			<td>
				<div>
					<em>
						<?php foreach ( $folders as $folder ): ?>
						<?php if ( $feed->folder_id == $folder->id ): ?>
						<?php echo $folder->title ?>
						<?php endif ?>
						<?php endforeach ?>
					</em>
					<select data-feed-id="<?php echo $feed->id ?>">
						<option value="">No folder</option>
						<?php foreach ( $folders as $folder ): ?>
						<option value="<?php echo $folder->id ?>"<?php echo $feed->folder_id == $folder->id ? ' selected="selected"' : '' ?>>
							<?php echo $folder->title ?>
						</option>
						<?php endforeach ?>
					</select>
				</div>
			</td>
			<td>
				<div>
					<br>
					<button class="btn btn-danger btn-small unsubscribe" data-feed-id="<?php echo $feed->id ?>" data-feed-name="<?php echo $feed->title ?>">Unsubscribe</button>
				</div>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>

<div class="divider"></div>
<?php else: ?>
<h3>Get started</h3>

<p>
	Add a few subscriptions to get started. Articles appear in &lsquo;<a href="<?php echo $this->app->getRootPath() ?>reading">My Reading</a>&rsquo;.
</p>

<table id="suggestions" class="table table-bordered table-striped table-hover">
	<tbody>
		<tr>
			<th>World News</th>
			<td><a href="https://www.nytimes.com/">The New York Times</a></td>
			<td><button class="subscribe btn btn-small" data-url="http://rss.nytimes.com/services/xml/rss/nyt/GlobalHome.xml">Subscribe</button></td>
		</tr>
		<tr>
			<th><br></th>
			<td><a href="http://boston.com/">Boston.com</a></td>
			<td><button class="subscribe btn btn-small" data-url="http://feeds.boston.com/boston/topstories">Subscribe</button></td>
		</tr>
		<tr>
			<th>Technology</th>
			<td><a href="https://www.techdirt.com/">Techdirt</a></td>
			<td><button class="subscribe btn btn-small" data-url="http://feeds.feedburner.com/techdirt/feed">Subscribe</button></td>
		</tr>
		<tr>
			<th><br></th>
			<td><a href="http://thenextweb.com/">The Next Web</a></td>
			<td><button class="subscribe btn btn-small" data-url="http://feeds2.feedburner.com/thenextwebtopstories">Subscribe</button></td>
		</tr>
		<tr>
			<th>Science</th>
			<td><a href="http://arstechnica.com/science/">Ars Technica - Scientific Method</a></td>
			<td><button class="subscribe btn btn-small" data-url="http://feeds.arstechnica.com/arstechnica/science?format=xml">Subscribe</button></td>
		</tr>
		<tr>
			<th><br></th>
			<td><a href="http://www.nasa.gov">NASA Breaking News</a></td>
			<td><button class="subscribe btn btn-small" data-url="http://www.nasa.gov/rss/breaking_news.rss">Subscribe</button></td>
		</tr>
		<tr>
			<th>Comics</th>
			<td><a href="https://xkcd.com/">xkcd</a></td>
			<td><button class="subscribe btn btn-small" data-url="https://xkcd.com/rss.xml">Subscribe</button></td>
		</tr>
		<tr>
			<th><br></th>
			<td><a href="http://pbfcomics.com/">The Perry Bible Fellowship</a></td>
			<td><button class="subscribe btn btn-small" data-url="http://pbfcomics.com/feed/feed.xml">Subscribe</button></td>
		</tr>
		<tr>
			<th>Sports</th>
			<td><a href="http://sports.yahoo.com/">Yahoo! Sports</a></td>
			<td><button class="subscribe btn btn-small" data-url="http://news.yahoo.com/rss/sports">Subscribe</button></td>
		</tr>
		<tr>
			<th><br></th>
			<td><a href="http://espn.go.com/">ESPN</a></td>
			<td><button class="subscribe btn btn-small" data-url="http://sports.espn.go.com/espn/rss/news">Subscribe</button></td>
		</tr>
		<tr>
			<th>Entertainment</th>
			<td><a href="http://www.bbc.com/news/entertainment_and_arts/">BBC News - Entertainment &amp; Arts</a></td>
			<td><button class="subscribe btn btn-small" data-url="http://feeds.bbci.co.uk/news/entertainment_and_arts/rss.xml">Subscribe</button></td>
		</tr>
		<tr>
			<th><br></th>
			<td><a href="https://www.google.com/news/section?topic=e">Google News - Entertainment</a></td>
			<td><button class="subscribe btn btn-small" data-url="https://www.google.com/news?pz=1&cf=all&ned=au&hl=en&topic=e&output=rss">Subscribe</button></td>
		</tr>
	</tbody>
</table>

<div class="divider"></div>
<?php endif ?>

<h3 id="feed-subscribe">Subscribe to feed</h3>

<p>
	Enter an <a href="https://en.wikipedia.org/wiki/RSS">RSS</a> or <a href="https://en.wikipedia.org/wiki/Atom_%28standard%29">Atom</a> feed URL to subscribe.
	Alternatively you can just use a website's URL and we'll try to find a feed automatically.
</p>

<form id="form-subscriptions-subscribe" method="post" action="<?php echo $this->app->getRootPath() ?>subscriptions" class="form-subscriptions form-horizontal well">
	<input type="hidden" name="form" value="subscribe">
	<input type="hidden" name="sessionId" value="<?php echo $this->app->getSingleton('session')->getId() ?>">

	<fieldset>
		<div class="control-group <?php echo $this->get('error-url') ? 'error' : '' ?>">
			<label class="control-label" for="url">URL</label>

			<div class="controls">
				<input id="url" name="url" class="input-block-level" type="text" value="<?php echo $this->get('url') ?>" placeholder="Website or feed URL">
			</div>
		</div>

		<div class="control-group <?php echo $this->get('error-url') ? 'error' : '' ?>">
			<label class="control-label" for="folder">Folder</label>

			<div class="controls">
				<select id="folder" name="folder">
					<option value="">No folder</option>
					<?php foreach ( $folders as $folder ): ?>
					<option value="<?php echo $folder->id ?>">
						<?php echo $folder->title ?>
					</option>
					<?php endforeach ?>
				</select>
			</div>
		</div>

		<div class="control-group">
			<div class="controls">
				<button class="btn btn-primary" type="submit">Subscribe</button><div class="loading"></div><span class="message"></span>
			</div>
		</div>
	</fieldset>
</form>

<div class="divider"></div>

<h3 id="folders">Manage folders</h3>

<p>
	Organise your subscriptions into folders. Folders are public and can be shared with anyone anonymously. Deleting a folder does not delete subscriptions.
</p>

<form id="form-folders" method="post" action="<?php echo $this->app->getRootPath() ?>subscriptions" class="form-folders form-horizontal well">
	<input type="hidden" name="form" value="folders">
	<input type="hidden" name="sessionId" value="<?php echo $this->app->getSingleton('session')->getId() ?>">

	<?php if ( $folders ): ?>
	<fieldset>
		<table id="folders" class="table table-bordered table-striped table-hover table-list">
			<tbody>
				<tr>
					<th>Folder</th>
					<th>Delete?</th>
				</tr>
				<?php foreach ( $folders as $folder ): ?>
				<tr>
					<td>
						<div>
							<div><?php echo $folder->title ?></div>

							<input type="text" name="titles[<?php echo $folder->id ?>]" value="<?php echo $folder->title ?>" class="input-block-level">
						</div>
					</td>
					<td>
						<input type="checkbox" name="delete[<?php echo $folder->id ?>]" value="1">
					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</fieldset>
	<?php endif ?>

	<fieldset>
		<div class="control-group <?php echo $this->get('error-title') ? 'error' : '' ?>">
			<label class="control-label" for="url">Folder name</label>

			<div class="controls">
				<input id="title" name="titles[new]" class="input-block-level" type="text" value="<?php echo $this->get('title') ?>" placeholder="E.g. News">
			</div>
		</div>

		<div class="control-group">
			<div class="controls">
				<button class="btn btn-primary" type="submit">Save folders</button><div class="loading"></div>
			</div>
		</div>
	</fieldset>
</form>

<div class="divider"></div>

<h3 id="feed-import-export">Import &amp; export feeds</h3>

<p>
	<em><a href="https://www.google.com/takeout/?pli=1#custom:reader">Export your <strong>Google Reader</strong> subscriptions through Google Takeout</a>. Extract the ZIP file
	and upload the file called &lsquo;subscriptions.xml&rsquo; (in a folder called &lsquo;Reader&rsquo;) below.</em>
</p>

<p>
	It may take up to a few hours for imported feeds to appear in &lsquo;<a href="<?php echo $this->app->getRootPath() ?>reading">My Reading</a>&rsquo;.
</p>

<form id="form-subscriptions-import" method="post" action="<?php echo $this->app->getRootPath() ?>subscriptions" class="form-horizontal well" enctype="multipart/form-data">
	<input type="hidden" name="form" value="import">
	<input type="hidden" name="sessionId" value="<?php echo $this->app->getSingleton('session')->getId() ?>">

	<fieldset>
		<div class="control-group">
			<label class="control-label" for="file">OPML File</label>

			<div class="controls">
				<input id="file" name="file" class="input-block-level" type="file">
			</div>
		</div>

		<div class="control-group">
			<div class="controls">
				<button class="btn btn-primary" type="submit">Import feeds</button>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<div class="control-group">
			<div class="controls">
				<a class="btn btn-primary" href="<?php echo $this->app->getRootPath() ?>subscriptions/export">Export feeds</a>
			</div>
		</div>
	</fieldset>
</form>

<?php require 'footer.html.php' ?>
