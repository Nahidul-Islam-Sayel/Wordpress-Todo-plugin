import { __ } from "@wordpress/i18n";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import "./editor.scss";
import {PanelBody, ToggleControl, QueryControls, RangeControl} from "@wordpress/components";
import {format, dateI18n, __experimentalGetSettings} from "@wordpress/date";
import { RawHTML } from '@wordpress/element';
import { useSelect } from "@wordpress/data";
export default function Edit({ attributes,setAttributes, Range }) {
	const { numberOfPosts, displayFeaturedImage } = attributes;
	const posts = useSelect(
		(select) => {
			return select('core').getEntityRecords('postType', 'post', {
				per_page: numberOfPosts,
				_embed: true,
			});
		},
		[numberOfPosts]
	);
	const onDisplayFeaturedImageChange=(value)=>{
		setAttributes({displayFeaturedImage: value})
	}
	const onNumberOfItemsChange=(value)=>{
		setAttributes({numberOfPosts: value})
	}


	return (
		<>
		<InspectorControls>
			<PanelBody>
				<ToggleControl label={__("Display Featured Image", "latest-posts")}
				checked={displayFeaturedImage}
				onChange={onDisplayFeaturedImageChange}
				
				/>
				<QueryControls numberOfItems={numberOfPosts} onNumberOfItemsChange={onNumberOfItemsChange}
				maxItems={10}
				minItems={1}
				orderBy="date"
				onOrderByChange={(value)=>console.log(value)}
				order="asc"
				onOrderChange={(value)=>console.log(value)}
				
			
				/>
			</PanelBody>
		</InspectorControls>
		<ul {...useBlockProps()}>
			{posts &&
				posts.map((post) => {
					const featuredImage =
						post._embedded &&
						post._embedded['wp:featuredmedia'] &&
						post._embedded['wp:featuredmedia'].length > 0 &&
						post._embedded['wp:featuredmedia'][0];
					return (
						<li key={post.id}>
							{displayFeaturedImage && featuredImage && (
								<img
									src={
										featuredImage.media_details.sizes.large
											.source_url
									}
									alt={featuredImage.alt_text}
								/>
							)}
							<h5>
								<a href={post.link}>
									{post.title.rendered ? (
										<RawHTML>{post.title.rendered}</RawHTML>
									) : (
										__('(No title)', 'latest-posts')
									)}
								</a>
							</h5>
							{post.date_gmt && (
								<time dateTime={format('c', post.date_gmt)}>
									{dateI18n(
										__experimentalGetSettings().formats
											.date,
										post.date_gmt
									)}
								</time>
							)}
							{post.excerpt.rendered && (
								<RawHTML>{post.excerpt.rendered}</RawHTML>
							)}
						</li>
					);
				})}
		</ul>

		</>
	);
}