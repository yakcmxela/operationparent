<?php 
		$queried_object = get_queried_object();
		$sortby = array(
			'Default Sorting' => 'menu_order',
			'Sort by Popularity' => 'popularity',
			'Sort by average rating' => 'rating',
			'Sort by newness' => 'date',
			'Sort by price: low to high' => 'price',
			'Sort by price: high to low' => 'price-desc'
		);

		$categories = get_terms( 'product_cat' );
		$filter_dropdown = '<div class="filter-container">';
		$filter_dropdown .= '<div class="filter-label">Filter By</div>';
		$filter_dropdown .= '<div class="filter-dropdown">';
		$filter_dropdown .= '<div class="filter-display">';
		$filter_dropdown .= 'Category';
		$filter_dropdown .= '</div>';
		$filter_dropdown .= '<ul>';
		$filter_dropdown .= '<li><a href="' . get_permalink(wc_get_page_id( 'shop' ) ) . '"">All</a></li>';
		foreach( $categories as $category ) {
			$is_brand = get_field('is_brand', $category);
			if($category->slug !== 'uncategorized' && $is_brand == false) :
				$caturl = get_category_link( $category->term_id );
				$catname = $category->name;

				$filter_dropdown .= '<li><a href="' . $caturl . '">' . $catname. '</a></li>';
			endif;
		}
		$filter_dropdown .= '</ul>';
		$filter_dropdown .= '</div>';

		// $filter_dropdown .= '<div class="filter-container sort">';
		// $filter_dropdown .= '<div class="filter-dropdown">';
		// $filter_dropdown .= '<div class="filter-display">';
		// $filter_dropdown .= 'Sort by';
		// $filter_dropdown .= '</div>';
		// $filter_dropdown .= '<ul>';
		// foreach( $sortby as $name => $sort ) {
		// 	if( is_product_category() ) {
		// 		$url = get_category_link( $queried_object ) . '?orderby=' . $sort;
		// 	} elseif( is_shop() ) {
		// 		$url = get_permalink(wc_get_page_id( 'shop' ) ) . '?orderby=' . $sort;
		// 	}
		// 	$filter_dropdown .= '<li><a href="' . $url .'">' . $name. '</a></li>';
		// }

		// $filter_dropdown .= '</ul>';
		// $filter_dropdown .= '</div>';
		// $filter_dropdown .= '</div>';
		// $filter_dropdown .= '</div>';
		echo $filter_dropdown;
?>