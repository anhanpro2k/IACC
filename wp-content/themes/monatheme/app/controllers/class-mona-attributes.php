<?php

class Variation {

	protected static $proId;
	protected static $product = [];
	protected static $available_variations = [];

	protected function variationHandling( $relationshipData ) {

		if ( empty( $relationshipData ) ) {
			return false;
		}

		$relationshipReturn = [];
		foreach ( $relationshipData as $relationshipAttributeName => $relationshipAttributeItems ) {
			$relationshipAttributeNameOffcial = str_replace( 'attribute_', '', $relationshipAttributeName );
			if ( ! empty( $relationshipAttributeItems ) ) {
				foreach ( $relationshipAttributeItems as $attribute_slug => $attribute_item ) {
					if ( $attribute_item['disabled'] == 'yes' ) {
						$relationshipReturn[ $relationshipAttributeNameOffcial ][] = $attribute_slug;
					}
				}
			}
		}

		return $relationshipReturn;
	}

	protected function variationHandlingEnable( $relationshipData ) {

		if ( empty( $relationshipData ) ) {
			return false;
		}

		$relationshipReturn = [];
		foreach ( $relationshipData as $relationshipAttributeName => $relationshipAttributeItems ) {
			$relationshipAttributeNameOffcial = str_replace( 'attribute_', '', $relationshipAttributeName );
			if ( ! empty( $relationshipAttributeItems ) ) {
				foreach ( $relationshipAttributeItems as $attribute_slug => $attribute_item ) {
					if ( $attribute_item['disabled'] == 'no' ) {
						$relationshipReturn[ $relationshipAttributeNameOffcial ][] = $attribute_slug;
					}
				}
			}
		}

		return $relationshipReturn;
	}

	public static function Html() {
		// default product id
		$productId = intval( self::$proId );
		if ( ! isset ( $productId ) || $productId <= 0 ) {
			return;
		}
		// get product
		$product         = self::$product;
		$variations      = self::Variations();
		$firstAttributes = self::firstAttributes( $productId );


		// check
		if ( ! empty ( $variations ) ) {
			ob_start(); ?>
			<?php
			foreach ( $variations as $key => $itemVariations ) {
				$i               = 1;
				$attribute_name  = str_replace( 'attribute_', '', $key );
				$attribute_label = wc_attribute_label( $attribute_name );
				if ( ! empty( $itemVariations ) ) {
					$attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
					$type         = get_option( "wc_attribute_type-$attribute_id" );
					if ( $key == 'attribute_pa_kich-thuoc' ) {
						?>
                        <div class="prodt-info-item">
                            <span class="prodt-info-tit block"><?php echo $attribute_label; ?>:</span>
                            <div class="prodt-info-ct">
                                <div class="prodt-select">
                                    <select required name="attributes[<?php echo $attribute_name; ?>]"
                                            class="select2choose">
										<?php
										foreach ( $itemVariations as $slug => $item ) {
											$termAttr      = get_term_by( 'slug', $item['slug'], $attribute_name );
											$img_variation = get_field( 'img_color_attr', $termAttr );
											$checked       = ( $i == 1 ) ? "selected" : "";
											?>
                                            <option value="<?php echo $item["slug"]; ?>"><?php echo $item['name']; ?></option>
											<?php $i ++;
										} ?>
                                    </select>
                                </div>
                            </div>
                        </div>
					<?php } else { ?>
                        <div class="prodt-info-item">
                            <span class="prodt-info-tit block"><?php echo $attribute_label; ?>:</span>
                            <div class="prodt-info-ct">
                                <div class="prodt-cl">
                                    <div class="color-box">
                                        <div class="color-list flex flex-wrap recheck-block">
											<?php
											$i = 1;
											foreach ( $itemVariations as $slug => $item ) {
												$termAttr      = get_term_by( 'slug', $item['slug'], $attribute_name );
												$img_variation = get_field( 'product_color_attr', $termAttr );
												$checked       = ( $i == 1 ) ? "checked" : "";
												$active        = ( $i == 1 ) ? "active" : "";
												?>
                                                <div class="color-item recheck-item <?php echo $active; ?>">
                                                    <div class="color-item-inner">
                                                        <input type="radio"
                                                               class="recheck-input" <?php echo $checked; ?> hidden
                                                               required
                                                               name="attributes[<?php echo $attribute_name; ?>]"
                                                               value="<?php echo $item["slug"]; ?>">
                                                        <div class="color-item-check"
                                                             style="background: <?php echo $img_variation; ?>">
                                                            <i class="fas fa-check"></i>
                                                        </div>
                                                    </div>
                                                </div>
												<?php $i ++;
											} ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<?php
					}
				}
			}
			?>
			<?php
			return ob_get_clean();
		}
	}

	public static function Args() {
		$variationsLists = $newLists = [];
		// default product id
		$productId = intval( self::$proId );
		if ( ! isset ( $productId ) || $productId <= 0 ) {
			return;
		}
		// get and check data
		$variationsLists = self::Variations( intval( self::$proId ) );
		if ( ! empty ( $variationsLists ) ) {
			$totalRows = count( $variationsLists );
			if ( $totalRows > 1 ) {
				$count = 0;
				foreach ( $variationsLists as $key => $itemValue ) {
					$attribute_name = str_replace( 'attribute_', '', $key );
					// default label
					$newLists[ $count ]['label']      = __( 'Chọn thuộc tính: ', 'monamedia' );
					$newLists[ $count ]['Variations'] = $itemValue;
					// after add label
					$newLists[ $count ]['label'] = __( 'Chọn', 'monamedia' ) . ' ' . wc_attribute_label( $attribute_name );
					$count ++;
				}
			} else {
				// default label
				$newLists['label'] = __( 'Chọn thuộc tính: ' );
				// add change new array
				foreach ( $variationsLists as $key => $itemValue ) {
					$attribute_name         = str_replace( 'attribute_', '', $key );
					$newLists['Variations'] = $itemValue;
				}
				// after add label
				$newLists['label'] = __( 'Chọn', 'monamedia' ) . ' ' . wc_attribute_label( $attribute_name );
			}
		}

		// result args
		return $newLists;
	}

	public static function setProId( int $productId ) {
		self::$product              = wp_cache_get( 'get_product_data' );
		self::$available_variations = wp_cache_get( 'get_available_variations' );
		if ( ! empty ( $productId ) ) {
			self::$proId = intval( $productId );
			if ( ! self::$product ) {
				self::$product = wc_get_product( $productId );
				wp_cache_set( 'get_product_data', self::$product, '', 3600 );
				if ( ! self::$available_variations ) {
					if ( self::$product->is_type( 'variable' ) ) {
						self::$available_variations = self::$product->get_available_variations();
						wp_cache_set( 'get_available_variations', self::$available_variations, '', 3600 );
					}
				}
			}
		} else {
			self::$proId                = 0;
			self::$product              = [];
			self::$available_variations = [];
		}

		return new self;
	}

	public static function firstAttributes() {
		// default product id
		$firstAttributes = [];
		$findAttributes  = wp_cache_get( 'find_first_attribute' );
		if ( ! $findAttributes ) {
			$productId = intval( self::$proId );
			if ( ! isset ( $productId ) || $productId <= 0 ) {
				return;
			}
			// get product
			$product = self::$product;
			if ( ! empty ( $product ) && $product->get_type() == 'variable' ) {
				$variations    = self::Variations();
				$typeVariation = count( $product->get_attributes() ) > 1 ? 'multiple' : 'single';
				if ( $typeVariation == 'single' ) {
					if ( ! empty ( $variations ) ) {
						$first = true;
						foreach ( $variations as $attribute_name => $items ) {
							foreach ( $items as $item_slug => $item ) {
								if ( $first ) {
									$firstAttributes[ $attribute_name ] = $item['slug'];
									$first                              = false;
								}
							}
						}
					}
				} elseif ( $typeVariation == 'multiple' ) {
					if ( ! empty ( $variations ) ) {
						$first = true;
						foreach ( $variations as $variation_attributes => $attributes_array ) {
							foreach ( $attributes_array as $key_attribute_slug => $attribute_item ) {
								if ( ! empty ( $attribute_item['first'] ) && $first ) {
									if ( $attribute_item['relationship'] ) {
										foreach ( $attribute_item['relationship'] as $key_relationship_slug => $relationship_items ) {
											if ( ! empty ( $relationship_items ) ) {
												foreach ( $relationship_items as $relationship_item_slug => $relationship_item ) {
													if ( $first && $relationship_item['disabled'] == 'no' && $relationship_item['variation_id'] != 0 && $relationship_item['variation_id'] > 0 ) {
														if ( isset ( $relationship_item['attributes'] ) ) {
															$firstAttributes = $relationship_item['attributes'];
														} else {
															$firstAttributes = [];
														}
														$first = false;
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
			wp_cache_set( 'find_first_attribute', $firstAttributes, '', 3600 );
		}

		return $firstAttributes;
	}

	public static function Variations() {
		// default product id
		$variation_attributes = [];
		$productId            = intval( self::$proId );
		if ( ! isset ( $productId ) || $productId <= 0 ) {
			return;
		}
		// get product
		ob_start();
		$product = self::$product;
		if ( $product->get_type() == 'variable' ) {
			$count     = 0;
			$totalAttr = count( $product->get_attributes() );
			foreach ( $product->get_attributes() as $attribute_name => $attribute ) {
				if ( isset ( $product->get_variation_attributes()[ $attribute_name ] ) ) {
					$childItemVariations = $product->get_variation_attributes()[ $attribute_name ];
					if ( ! empty ( $childItemVariations ) ) {
						foreach ( $childItemVariations as $varChildKey => $varChildValue ) {
							$varChildTerm = get_term_by( 'slug', $varChildValue, str_replace( 'attribute_', '', $attribute_name ) );
							if ( ! empty ( $varChildTerm ) ) {
								$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ] = [
									'term_id' => $varChildTerm->term_id,
									'name'    => $varChildTerm->name,
									'slug'    => $varChildTerm->slug,
								];
								// check first
								if ( $totalAttr <= 1 && $varChildKey == 0 ) {
									$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['first'] = true;
								} elseif ( $totalAttr > 1 && $count == 0 ) {
									$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['first'] = true;
								} else {
									$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['first'] = false;
								}
								// relation
								$listAttributeRelations = self::getAttributeRelation( $attribute_name, $varChildValue );
								if ( ! empty ( $listAttributeRelations ) ) {
									$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['relationship'] = $listAttributeRelations;
								} else {
									$findArgs                                                                                 = [ 'attribute_' . $attribute_name => $varChildTerm->slug ];
									$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['variation_id'] = self::findVariationId( $productId, $findArgs );
								}
							}
						}
					}
				}
				$count ++;
			}
		}

		return $variation_attributes;
	}

	private static function getAttributeRelation( $attributUnset = '', $attributValue = '' ) {
		// check empty
		if ( empty ( $attributUnset ) || empty ( $attributValue ) ) {
			return false;
		}
		// default product id
		$findAttributes       = [];
		$variation_attributes = [];
		$productId            = intval( self::$proId );
		if ( ! isset ( $productId ) || $productId <= 0 ) {
			return;
		}
		// get product
		$product = self::$product;
		foreach ( $product->get_attributes() as $attribute_name => $attribute ) {
			if ( isset ( $product->get_variation_attributes()[ $attribute_name ] ) ) {
				$childItemVariations = $product->get_variation_attributes()[ $attribute_name ];
				if ( ! empty ( $childItemVariations ) && $attribute_name != $attributUnset ) {
					foreach ( $childItemVariations as $varChildKey => $varChildValue ) {
						$varChildTerm = get_term_by( 'slug', $varChildValue, str_replace( 'attribute_', '', $attribute_name ) );
						if ( ! empty ( $varChildTerm ) ) {
							$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ] = [
								'term_id' => $varChildTerm->term_id,
								'name'    => $varChildTerm->name,
							];
							// array
							$totalCheck     = 0;
							$listCheckItems = self::checkAttributeRelation( $attributUnset, $attributValue );
							if ( ! empty ( $listCheckItems ) ) {
								$totalCheck = count( $listCheckItems );
								if ( count( $listCheckItems ) >= 2 ) {
									unset( $listCheckItems[ 'attribute_' . $attribute_name ] );
								}
								// check
								if ( $totalCheck <= 1 ) {
									foreach ( $listCheckItems as $checkkey => $subCheckItems ) {
										if ( ! empty ( $subCheckItems ) ) {
											$count = 0;
											foreach ( $subCheckItems as $subCheckKey => $subCheckValue ) {
												$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['compare'][ $count ][ 'attribute_' . $attributUnset ]  = $attributValue;
												$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['compare'][ $count ][ 'attribute_' . $attribute_name ] = $varChildValue;
												$count ++;
											}
										}
									}
								} else {
									foreach ( $listCheckItems as $checkkey => $subCheckItems ) {
										if ( ! empty ( $subCheckItems ) ) {
											$count = 0;
											foreach ( $subCheckItems as $subCheckKey => $subCheckValue ) {
												$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['compare'][ $count ][ 'attribute_' . $attributUnset ]  = $attributValue;
												$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['compare'][ $count ][ 'attribute_' . $attribute_name ] = $varChildValue;
												$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['compare'][ $count ][ $checkkey ]                      = $subCheckValue['slug'];
												$count ++;
											}
										}
									}
								}
							} else {
								$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['compare'] = [];
							}
							// check disabled
							$disabled         = 'yes';
							$variation_id     = 0;
							$checkListCompare = $variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['compare'];
							if ( ! empty ( $checkListCompare ) ) {
								foreach ( $checkListCompare as $comKey => $comAttrbutes ) {
									$findVariationId = self::findVariationId( $productId, $comAttrbutes );
									if ( ! empty ( $findVariationId ) && $findVariationId > 0 ) {
										$disabled                                                                               = 'no';
										$variation_id                                                                           = $findVariationId;
										$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['attributes'] = $comAttrbutes;
										continue;
									}
									// }else{
									//     $disabled       =   'yes';
									//     $variation_attributes['attribute_' . $attribute_name][$varChildValue]['attributes'] = $comAttrbutes;
									// }
								}
							}
							// result status
							$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['disabled']     = $disabled;
							$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['variation_id'] = $variation_id;
							// unset
							unset( $variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ]['compare'] );
						}
					}
				}
			}
		}

		return $variation_attributes;
	}

	private static function checkAttributeRelation( $attributUnset = '', $attributValue = '' ) {
		// check empty
		if ( empty ( $attributUnset ) || empty ( $attributValue ) ) {
			return false;
		}
		// default product id
		$findAttributes       = [];
		$variation_attributes = [];
		$productId            = intval( self::$proId );
		if ( ! isset ( $productId ) || $productId <= 0 ) {
			return;
		}
		// get product
		$product = self::$product;
		foreach ( $product->get_attributes() as $attribute_name => $attribute ) {
			if ( isset ( $product->get_variation_attributes()[ $attribute_name ] ) ) {
				$childItemVariations = $product->get_variation_attributes()[ $attribute_name ];
				if ( ! empty ( $childItemVariations ) && $attribute_name != $attributUnset ) {
					foreach ( $childItemVariations as $varChildKey => $varChildValue ) {
						$varChildTerm = get_term_by( 'slug', $varChildValue, str_replace( 'attribute_', '', $attribute_name ) );
						if ( ! empty ( $varChildTerm ) ) {
							$variation_attributes[ 'attribute_' . $attribute_name ][ $varChildValue ] = [
								'term_id' => $varChildTerm->term_id,
								'name'    => $varChildTerm->name,
								'slug'    => $varChildTerm->slug,
							];
						}
					}
				}
			}
		}

		return $variation_attributes;
	}

	public static function findVariationId( $productId = '', $attributes = [] ) {
		$data_store  = WC_Data_Store::load( 'product' );
		$variationId = $data_store->find_matching_product_variation( new \WC_Product( $productId ), $attributes );

		return $variationId;
	}

}