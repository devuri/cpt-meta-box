<?php

namespace DevUri\PostTypeMeta\Traits;

trait StyleTrait
{
    /**
     * Table styles.
     *
     * @param $args
     */
    public function table_style( bool $zebra ): void
    {
        if ( $zebra ) {
            $this->zebra_table();

            return;
        }
        $this->table_css();
    }

    /**
     * Table styles.
     */
    public function table_css(): void
    {
        ?><style media="all">
			#cpm-post-meta-form table {
				border-collapse: collapse;
				width: 100%;
			}
			#cpm-post-meta-form th, td {
				text-align: left;
				padding: 12px;
			}
		</style>
        <?php
    }

    /**
     * zebra table.
     *
     * @return void
     */
    public function zebra_table(): void
    {
        ?>
        <style media="all">
 		   #cpm-post-meta-form table {
 			   border-collapse: collapse;
 			   width: 100%;
 		   }
 		   #cpm-post-meta-form th, td {
 			   text-align: left;
 			   padding: 12px;
 		   }
 		   #cpm-post-meta-form tbody tr:nth-child(even) {
 			   background: #f6f7f7;
 		   }
 		   #cpm-post-meta-form tbody tr:nth-child(even) {
 			   border-top: solid thin #eaeaea;
 			   border-bottom: solid thin #eaeaea;
 		   }
 	   </style>
		<?php
    }
}
