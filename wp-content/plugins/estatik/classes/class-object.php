<?php

/**
 * Class Es_Object
 */
class Es_Object
{
	/**
	 * Initialize object.
	 */
	public static function init()
	{
		$o = new static();

		$o->actions();
		$o->filters();

		return $o;
	}

    /**
     * Add object actions.
     *
     * @return void
     */
    public function actions() {}

    /**
     * Add object filters.
     *
     * @return void
     */
    public function filters() {}

    /**
     * Helper function for pushing new columns.
     *
     * @param array $column
     *    name => label array column.
     * @param array &$list
     *    Current array of all columns.
     * @param $index
     *    Index of pushed element.
     *
     * @return array
     *    Array with pushed column by index.
     */
    public static function push_column( $column, $list, $index )
    {
        return array_merge( array_slice( $list, 0, $index ), $column, array_slice( $list,$index ) );
    }
}
