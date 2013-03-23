<?php

namespace Swiftlet\Controllers;

class Feed extends \Swiftlet\Controllers\Read
{
	protected
		$title = 'Feed'
		;

	/**
	 * Default action
	 */
	public function index()
	{
		$this->getItems();
	}

	/**
	 * Get personal items
	 */
	public function items()
	{
		$this->view->name = 'read';

		$this->getItems();
	}

	/**
	 * Get personal items
	 */
	protected function getItems()
	{
		$userId = $this->app->getSingleton('session')->get('id');

		$excludes = !empty($_GET['excludes']) ? explode(' ', $_GET['excludes']) : array();
		$page     = !empty($_GET['page'])     ? (int)        $_GET['page']      : 1;

		$args = $this->app->getArgs();

		$feedId = !empty($args[0]) ? $args[0] : null;

		$dbh = $this->app->getSingleton('pdo')->getHandle();

		$select = '
      SELECT
				feeds.id         AS feed_id,
				feeds.title      AS feed_title,
				feeds.link       AS feed_link,
				items.id,
				items.url,
				items.title,
				items.contents,
				items.posted_at,
				0                AS feed_subscribed,
				0                AS vote,
				0                AS saved,
				COALESCE(AVG(users_items.score), 0) AS score
			FROM             items
      INNER JOIN       feeds ON       feeds.id      = items.feed_id
			LEFT  JOIN users_items ON users_items.item_id = items.id
			WHERE
				feeds.id = ?
				' . ( $excludes ? 'AND items.id NOT IN ( ' . implode(', ', array_fill(0, count($excludes), '?')) . ' )' : '' ) . '
			GROUP BY items.id
      ORDER BY DATE(items.posted_at) DESC, AVG(users_items.score) DESC
			';

		if ( $userId ) {
			$select = '
				SELECT
					main.*,
					COALESCE(users_items.vote,  0)   AS vote,
					COALESCE(users_items.saved, 0)   AS saved,
					IF(users_feeds.id IS NULL, 0, 1) AS feed_subscribed
				FROM ( ' . $select . ' ) AS main
				LEFT JOIN users_items ON users_items.item_id = main.id      AND users_items.user_id = ?
				LEFT JOIN users_feeds ON users_feeds.feed_id = main.feed_id AND users_feeds.user_id = ?
				WHERE
					users_items.read != 1 OR users_items.read IS NULL
				';
		}

		$select .= ' LIMIT ' . ( ( $page - 1 ) * self::ITEMS_PER_PAGE ) . ', ' . ( $page * self::ITEMS_PER_PAGE );

		$sth = $dbh->prepare($select);

		$i = 1;

		$sth->bindParam($i ++, $feedId);

		foreach( $excludes as $key => $itemId ) {
			$sth->bindParam($i ++, $excludes[$key]);
		}

		if ( $userId ) {
			$sth->bindParam($i ++, $userId);
			$sth->bindParam($i ++, $userId);
		}

		$sth->execute();

		$result = $sth->fetchAll(\PDO::FETCH_OBJ);

		$items = $result;

		foreach ( $items as $item ) {
			$this->view->set('pageTitle', $item->feed_title);
			$this->view->set('title',     $item->feed_title);
			$this->view->set('link',      $item->feed_link);

			$this->purify($item->contents);
			$this->localize($item->posted_at);
		}

		$this->view->set('items', $items);
	}
}