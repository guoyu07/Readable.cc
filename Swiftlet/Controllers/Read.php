<?php

namespace Swiftlet\Controllers;

class Read extends \Swiftlet\Controller
{
	const
		ITEMS_PER_PAGE = 5
		;

	/**
	 * Default action
	 */
	public function index()
	{
		header('HTTP/1.0 403 Forbidden');

		exit;
	}

	/**
	 * Register vote
	 */
	public function vote()
	{
		header('Content-type: application/json');

		$userId = $this->app->getSingleton('helper')->ensureValidUser(true);

		$itemId = isset($_POST['item_id']) ? (int) $_POST['item_id'] : null;
		$vote   = isset($_POST['vote'])    ? (int) $_POST['vote']    : null;

		if ( !$itemId || $vote < -1 || $vote > 1 ) {
			header('HTTP/1.0 400 Bad Request');

			exit(json_encode(array('message' => 'Invalid arguments')));
		}

		$dbh = $this->app->getSingleton('pdo')->getHandle();

		$sth = $dbh->prepare('
      INSERT IGNORE INTO users_items (
        user_id,
        item_id,
        vote
      ) VALUES (
				:user_id,
				:item_id,
				:vote
      )
      ON DUPLICATE KEY UPDATE
        vote = :vote
			;');

		$sth->bindParam('user_id', $userId);
		$sth->bindParam('item_id', $itemId);
		$sth->bindParam('vote',    $vote);

		try {
			$sth->execute();
		} catch ( \Exception $e ) {
			header('HTTP/1.0 500 Server Error');

			exit(json_encode(array('message' => 'Something went wrong, please try again.')));
		}

		exit(json_encode(array()));
	}

	/**
	 * Mark item as read
	 */
	public function read()
	{
		header('Content-type: application/json');

		$userId = $this->app->getSingleton('helper')->ensureValidUser(true);

		$itemId = isset($_POST['item_id']) ? (int) $_POST['item_id'] : null;
		$read   = isset($_POST['read'])    ? (int) $_POST['read']    : null;

		if ( !$itemId || ( $read != 0 && $read != 1 ) ) {
			header('HTTP/1.0 400 Bad Request');

			exit(json_encode(array('message' => 'Invalid arguments')));
		}

		$dbh = $this->app->getSingleton('pdo')->getHandle();

		$sth = $dbh->prepare('
      INSERT IGNORE INTO users_items (
        user_id,
        item_id,
        `read`
      ) VALUES (
				:user_id,
				:item_id,
				:read
      )
      ON DUPLICATE KEY UPDATE
        `read` = :read
			;');

		$sth->bindParam('user_id', $userId);
		$sth->bindParam('item_id', $itemId);
		$sth->bindParam('read',    $read);

		try {
			$sth->execute();
		} catch ( \Exception $e ) {
			header('HTTP/1.0 500 Server Error');

			exit(json_encode(array('message' => 'Something went wrong, please try again.')));
		}

		exit(json_encode(array()));
	}

	/**
	 * Subscribe to feed
	 */
	public function subscribe()
	{
		header('Content-type: application/json');

		$userId = $this->app->getSingleton('helper')->ensureValidUser(true);

		$feedId = isset($_POST['feed_id']) ? (int) $_POST['feed_id'] : null;
		$action = isset($_POST['action'])  ?       $_POST['action']  : null;

		if ( !$feedId || ( $action != 'subscribe' && $action != 'unsubscribe' ) ) {
			header('HTTP/1.0 400 Bad Request');

			exit(json_encode(array('message' => 'Invalid arguments')));
		}

		$dbh = $this->app->getSingleton('pdo')->getHandle();

		if ( $action == 'subscribe' ) {
			$sth = $dbh->prepare('
				INSERT IGNORE INTO users_feeds (
					user_id,
					feed_id
				) VALUES (
					:user_id,
					:feed_id
				)
				;');
		} else {
			$sth = $dbh->prepare('
				DELETE
				FROM users_feeds
				WHERE
					user_id = :user_id AND
					feed_id = :feed_id
				LIMIT 1
				;');
		}

		$sth->bindParam('user_id', $userId);
		$sth->bindParam('feed_id', $feedId);

		try {
			$sth->execute();
		} catch ( \Exception $e ) {
			header('HTTP/1.0 500 Server Error');

			exit(json_encode(array('message' => 'Something went wrong, please try again.')));
		}

		exit(json_encode(array()));
	}

	/**
	 * Sanitise HTML
	 *
	 * @param string $html
	 * @return string
	 */
	protected function purify(&$html)
	{
		require_once 'HTMLPurifier/Bootstrap.php';

		// Remove FeedBurner cruft
		$html = preg_replace('/(<div class="feedflare.+?<\/div>|<img[^>]+?(feedsportal|feedburner)\.com[^>]+?>)/s', '', $html);

		$config = \HTMLPurifier_Config::createDefault();

		$config->set('HTML.Allowed', 'h1,h2,h3,h4,h5,h6,a[href],p,ul,ol,li,blockquote,em,i,strong,b,img[src],pre,code,table,thead,tbody,tfoot,tr,th,td');
		$config->set('AutoFormat.AutoParagraph', true);
		$config->set('AutoFormat.RemoveEmpty', true);
		$config->set('AutoFormat.RemoveEmpty.RemoveNbsp', true);
		$config->set('HTML.SafeObject', true);
		$config->set('Output.FlashCompat', true);

		$purifier = new \HTMLPurifier($config);

		$html = $purifier->purify($html);

		$html = preg_replace('/<table>/', '<table class="table table-bordered table-striped table-hover">', $html);
	}
}