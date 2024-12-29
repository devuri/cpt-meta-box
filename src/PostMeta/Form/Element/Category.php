<?php

namespace Urisoft\PostMeta\Form\Element;

trait Category
{
    /**
     * Custom version of the WP Dropdown Category list.
     *
     * @param string $fieldname field name
     * @param array  $args      define custom arguments
     *
     * @return string
     *
     * @see https://developer.wordpress.org/reference/functions/wp_dropdown_categories/
     */
    public function categorylist($fieldname = null, $args = []): string
    {
        return \sprintf(
            '<!-- input select field %s -->
			<tr class="input-select">
			<th><label for="select_dropdown">Select a Category</label></th>
			<td> %s </td>
			</tr>',
            $fieldname,
            wp_dropdown_categories($this->categoryOptions($fieldname, $args))
        );
    }

    /**
     * Hidden input field for create category.
     *
     * @param string $class additional class.
     *
     * @return void
     */
    public function createCategory(string $class = ''): void
    {
        ?><tr class="input-create-category <?php echo esc_attr($class); ?>"><th>
				<label for="create_category">Create Category</label>
			</th>
				<td>
					<input type="text" name="create_category" id="create_category" aria-describedby="create-category" value=" " class="hidden-cls">
				<p class="description" id="create-category">create category</p>
			</td>
		</tr>
		<?php
    }

    /**
     * Just get the args.
     *
     * @return (false|int|string)[]
     *
     * @psalm-return array{show_option_all: '', show_option_none: '', option_none_value: '-1', orderby: 'ID', order: 'ASC', show_count: 0, hide_empty: 1, child_of: 0, exclude: '', echo: 0, selected: 0, hierarchical: 0, name: string, id: '', class: 'uk-select', depth: 0, tab_index: 0, taxonomy: 'category', hide_if_empty: false, value_field: 'term_id'}
     */
    protected static function categoryOptions(string $fieldname, array $args): array
    {
        return [
            'show_option_all'   => '',
            'show_option_none'  => '',
            'option_none_value' => '-1',
            'orderby'           => 'ID',
            'order'             => 'ASC',
            'show_count'        => 0,
            'hide_empty'        => 1,
            'child_of'          => 0,
            'exclude'           => '',
            'echo'              => 0,
            'selected'          => 0,
            'hierarchical'      => 0,
            'name'              => strtolower(str_replace(' ', '_', $fieldname)) . 'set_category',
            'id'                => '',
            'class'             => 'uk-select',
            'depth'             => 0,
            'tab_index'         => 0,
            'taxonomy'          => 'category',
            'hide_if_empty'     => false,
            'value_field'       => 'term_id',
        ];
    }
}
