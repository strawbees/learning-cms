# Consuming data
Clients can consume data from the Wordpress installation via it's built-in REST
API, or via a GraphlQL endpoint.

## REST API
The API is available in the endpoint `/wp-json/wp/v2`.

Since we just use Wordpress' built-in REST API. So you can read more about it's
documentation [here](https://developer.wordpress.org/rest-api/).

The responses of the API are unmodified, except for the addition to of the field
`blocks` to the data of Posts and Pages. You can read more about the blocks in
the [Gutenberg Blocks](#gutenberg-blocks) section of this guide.

## GraphQL
GraphQL is available in the endpoint `/graphql`.

Since we use the plugin WPGraphQL you can read more about it's documentation
[here](https://docs.wpgraphql.com/).

The responses are unmodified, except for the addition to of the field
`blocksRaw` to the data of Posts and Pages. You can read more about the blocks
in the [Gutenberg Blocks](#gutenberg-blocks) section of this guide.

### Sample Queries
A good way to understand how to best use GraphQL is to look at the queries that
are currently being used on the website.

There are basically only three queries used. One that fetches general
information of the website (menus, metadata about categories, redirects, etc).
And two queries that fetch all the data necessary to render a Post or a Page
route. The Post and Page queries are almost identical except by the id key
(`pageId` vs `postId`)

#### General Query
```graphql
query General {
  posts(where: {status: PUBLISH}) {
    nodes {
      ...PostBasic
    }
    postTypeInfo {
      name
    }
  }
  pages(where: {status: PUBLISH}) {
    nodes {
      ...PageBasic
      childPages {
        nodes {
          ...PageBasic
          childPages {
            nodes {
              ...PageBasic
              childPages {
                nodes {
                  ...PageBasic
                }
              }
            }
          }
        }
      }
    }
    postTypeInfo {
      name
    }
  }
  headerMenus: menus(where: {location: HEADER_MENU}) {
    nodes {
      ...MenuBasic
    }
  }
  footerMenus: menus(where: {location: FOOTER_MENU}) {
    nodes {
      ...MenuBasic
    }
  }
  tags {
    nodes {
      ...TagBasic
    }
  }
  categories {
    nodes {
      ...CategoryBasic
      children {
        nodes {
          ...CategoryBasic
          children {
            nodes {
              ...CategoryBasic
              children {
                nodes {
                  ...CategoryBasic
                }
              }
            }
          }
        }
      }
    }
  }
  configurations(first: 1) {
    nodes {
      configuration {
        baseUrl
        cdnUrl
        frontendUrl
        redirects
        footerNotice
        homePage {
          ... on Page {
            id : pageId
          }
        }
        productCategories {
          ... on Category {
            id : categoryId
          }
          ... on Tag {
            id : tagId
          }
        }
        creativeLearningCategories {
          ... on Category {
            id: categoryId
          }
          ... on Tag {
            id: tagId
          }
        }
        explorationCategories {
          ... on Category {
            id: categoryId
          }
          ... on Tag {
            id: tagId
          }
        }
      }
      editorColorPalette {
          slug
          color
      }
    }
  }
}

fragment CategoryBasic on Category {
  id: categoryId

  name
  colors {
    primaryColor
  }
}

fragment TagBasic on Tag {
  id: tagId

  name
  colors {
    primaryColor
  }
}

fragment PageBasic on Page {
  id: pageId

  title
  link
  excerpt
  acl {
    aclEnabled
    aclRoles
  }
  colors {
    primaryColor
  }
  featuredImage {
    ...Media
  }
  terms {
    ... on Category {
      id: categoryId
      taxonomy {
        name
      }
    }
    ... on Tag {
      id: tagId
      taxonomy {
        name
      }
    }
  }
}

fragment PostBasic on Post {
  id: postId

  title
  link
  excerpt
  acl {
    aclEnabled
    aclRoles
  }
  colors {
    primaryColor
  }
  featuredImage {
    ...Media
  }
  terms {
    ... on Category {
      id: categoryId
      taxonomy {
        name
      }
    }
    ... on Tag {
      id: tagId
      taxonomy {
        name
      }
    }
  }
}

fragment Media on MediaItem {
  mediaType
  mediaItemUrl
  mediaDetails {
    sizes {
      name
      sourceUrl
    }
  }
  mimeType
}

fragment MenuBasic on Menu {
  id: slug
  menuItems {
    nodes {
      label
      url
      target
      cssClasses
      childItems {
        nodes {
          label
          url
          target
          cssClasses
        }
      }
    }
  }
}
```
#### Page Query
```graphql
query Page($id: ID) {
  post : pageBy(pageId:$id) {
    id: postId
    title
    link
    excerpt
    featuredImage {
      mediaType
      mediaItemUrl
      mediaDetails {
        sizes {
          name
          sourceUrl
        }
      }
      mimeType
    }
    terms {
      ... on Category {
        id: categoryId
        taxonomy {
          name
        }
      }
      ... on Tag {
        id: tagId
        taxonomy {
          name
        }
      }
    }
    blocksRaw
    og {
      ogDescription
      ogTitle
      ogImage {
        mediaType
        mediaItemUrl
        mediaDetails {
          sizes {
            name
            sourceUrl
          }
        }
        mimeType
      }
    }
  }
}
```
#### Post Query
```graphql
query Post($id: ID) {
  post : postBy(postId:$id) {
    id: postId
    title
    link
    excerpt
    featuredImage {
      mediaType
      mediaItemUrl
      mediaDetails {
        sizes {
          name
          sourceUrl
        }
      }
      mimeType
    }
    terms {
      ... on Category {
        id: categoryId
        taxonomy {
          name
        }
      }
      ... on Tag {
        id: tagId
        taxonomy {
          name
        }
      }
    }
    blocksRaw
    og {
      ogDescription
      ogTitle
      ogImage {
        mediaType
        mediaItemUrl
        mediaDetails {
          sizes {
            name
            sourceUrl
          }
        }
        mimeType
      }
    }
  }
}
```

# How is the data organised inside WordPress?

## Post Type: Post
All "Products", "Lesson Plans", "Activities" and "Explorations".

#### Built-in data:
- Title
- Excerpt
- Featured Image
- Permalink
- Categories

#### Advanced Custom Fields:
- OG
	- OG Title
	- OG Description
	- OG Image
- Colors
	- Primary Color (mostly relevant for "Products")

#### Blocks
The post "content" is available as parsed tree of blocks (see the
[Gutenberg Blocks](#gutenberg-blocks) section for more information on the blocks
available).

## Post Type: Page
The "Home" page, all "Support" pages, "About", etc.

Pages work exactly like the Posts, with the exception that you can't use
categories and a page can have multiple child pages.

## Post Type: Configuration
General site configurations. There should be **only one post** of this type
(named "Main"), that holds all the information. Think about this single post as
a "Settings page" (in fact it would be more adequate to use a Settings page
instead of a post type for this type of information, however a post type is much
easier to expose in the REST API and GraphQL, hence this compromise).

#### Advanced Custom Fields:
- Base URL (the address of the CMS itself)
- CDN URL (the address of the CDN)
- Frontend URL (used for previewing edits)
- Home Page (which page is being used as the home page)
- Product Categories
- Creative Learning Categories
- Exploration Categories
- Footer Notice
- Redirects

## Taxonomy: Category
Define the "types" of posts available. Categories can be "Products", "Lesson Plans", etc.

#### Built-in data:
- Title
- Slug
- Parent

#### Advanced Custom Fields:
- Colors
	- Primary Color

## Appearance: Menus
There are two menus locations registered: `header_menu` and `footer_menu`.

#### Built-in data (for each item in the menu):
- Label
- URL
- Target
- CSS Classes
- Child Items

# Gutenberg Blocks
The "body" of the posts/pages is represented by a tree of
[Gutenberg Blocks](https://developer.wordpress.org/block-editor/). The blocks data is exposed in both the REST API and in GraphQL:

- **REST API:** `blocks` field (JSON object)
- **GraphQL:** `blocksRaw` field (String - serialised JSON object)

The structure of the each block in the tree is represented by the schema:
```
{
	name (String - the name of the block)
	data (Object - key/value pairs with necessary data)
	innerBlocks (Array - other nested blocks)
}
```
### The `data` field
The `data` field contains all the data necessary to render the block. As each
block has it own specific subfields, the shape of `data` can vary a lot. However
there are some fields that are repeated and have a established.

#### Common `data` subfields

##### Subfield `sizeFormat`
Represents how the block should be rendered. Possible values are `normal`,
`wide` and `full`.

##### Subfield `horizontalAligment`
Mostly used on text blocks. Possible values are `center`, `left` and `right`.

##### Subfield `verticalAlignment`
Possible values are `center`, `top` and `bottom`.

##### Subfield `color`, `backgroundColor`, `textColor`, etc
A CSS color value.

##### Subfield `innerHTML`
A parsed tree of the HTML structure. It can be either an array, an object or
null.

The best way to understand the tree structure, is to see how examples of the raw
HTML and the parsed structure side by side:


###### `<div>test</div>`
```
{
	"tag": "div",
	"innerChildren": [
		{
			"tag": "text",
			"text": "test"
		}
	]
}
```
###### `<div>a</div><div>b</div>`
```
[
	{
		"tag": "div",
		"innerChildren": [
			{
				"tag": "text",
				"text": "a"
			}
		]
	},
	{
		"tag": "div",
		"innerChildren": [
			{
				"tag": "text",
				"text": "b"
			}
		]
	}
]
```

###### `<a href="/">link</a>`
```
{
	"tag": "a",
	"attributes": {
		"href": "/"
	},
	"innerChildren": [
		{
			"tag": "text",
			"text": "link"
		}
	]
}
```
###### `<ul class="list"><li>1</li><li>2</li></ul>`
```
{
	"tag": "ul",
	"attributes": {
		"class": "list"
	},
	"innerChildren": [
		{
			"tag": "li",
			"innerChildren": [
				{
					"tag": "text",
					"text": "1"
				}
			]
		},
		{
			"tag": "li",
			"innerChildren": [
				{
					"tag": "text",
					"text": "2"
				}
			]
		}
	]
}
```

## Examples of Blocks
### Custom Blocks
#### File Download
```
{
	"name": "acf/file-download",
	"data": {
		"title": "Download our pipeline",
		"extension": "pdf",
		"url": "https://d3lgho14bh7roi.cloudfront.net/app/uploads/2019/11/Strawbees-Pipeline-2020.pdf"
	},
	"innerBlocks": []
}
```
#### Posts List
```
{
	"name": "acf/posts-list",
	"data": {
		"sizeFormat": "normal",
		"posts": [
			{
				"id": "93",
				"isProduct": false,
				"title": "City Building",
				"excerpt": "Lorem ipsum sit dolor amet lalalal lololo ipsum sit dolor amet lalalal lololo ipsum sit dolor amet lalalal lololo.",
				"link": "/lesson-plan/city-building/",
				"color": "#333",
				"label": "Lesson Plan",
				"labelColor": "#feb702",
				"imageUrl": "https://d3lgho14bh7roi.cloudfront.net/app/uploads/2019/11/DSCF1475bridge_retouched_2Bridges_b01-1024x980.jpg"
			},
			{
				"id": "41",
				"isProduct": true,
				"title": "Strawbees Classroom Experience: Bridges",
				"excerpt": "",
				"link": "/product/strawbees-classroom-experience-bridges/",
				"color": "#ff7381",
				"label": "Product",
				"labelColor": "#ff7381",
				"imageUrl": "https://d3lgho14bh7roi.cloudfront.net/app/uploads/2019/11/Strawbees-Nordic-Edtech-Award-1024x576.jpg"
			}
		]
	},
	"innerBlocks": []
}
```
#### Product Card
```
{
	"name": "acf/product-card",
	"data": {
		"title": "Strawbees Classroom Experiences: Bridges",
		"color": "#333",
		"imageUrl": "https://d3lgho14bh7roi.cloudfront.net/app/uploads/2019/11/8c909fa32cb8a342e1d0daeae5949931-1024x181.png",
		"excerpt": "With this kit you learn the basic concepts of bridge design. An engaging hands-on learning experience!",
		"buyButtonLink": "https://strawbees.com/store",
		"buyButtonLabel": "Buy now"
	},
	"innerBlocks": []
}
```
### Core Blocks
#### Embed - Youtube
```
{
	"name": "core-embed/youtube",
	"data": {
		"sizeFormat": "normal",
		"url": "https://www.youtube.com/watch?v=ubChdzfykHg",
		"innerHTML": [
			{
				"tag": "text",
				"text": "Build the platonic solids"
			}
		]
	},
	"innerBlocks": []
}
```
#### Button
```
{
	"name": "core/button",
	"data": {
		"icon": "pdf",
		"textAlign": "left",
		"textColor": "#4D4D4D",
		"outlineColor": "#4D4D4D",
		"backgroundColor": "rgba(0,0,0,0)",
		"link": "/creative-content",
		"innerHTML": [
			{
				"tag": "text",
				"text": "Creative Content"
			}
		]
	},
	"innerBlocks": []
}
```
#### Columns
```
{
	"name": "core/columns",
	"data": {
		"sizeFormat": "normal"
	},
	"innerBlocks": [
		{
			"name": "core/column",
			...
		},
		{
			"name": "core/column",
			...
		}
	]
}
```
#### Column
```
{
	"name": "core/column",
	"data": {
		"verticalAlignment": "top",
		"width": 50
	},
	"innerBlocks": [...]
},
```
#### Cover
```
{
	"name": "core/cover",
	"data": {
		"sizeFormat": "full",
		"backgroundImageUrl": "https://d3lgho14bh7roi.cloudfront.net/app/uploads/2019/11/DSCF1525untitled-scaled.jpg",
		"backgroundImageParalax": false,
		"backgroundImagePosition": "24.117647058824% 100%",
		"overlayColor": "#FFC016",
		"overlayOpacity": 0.3,
		"minHeight": 300
	},
	"innerBlocks": [...]
}
```
#### Gallery
```
{
	"name": "core/gallery",
	"data": {
		"sizeFormat": "normal",
		"items": [
			{
				"url": "https://d3lgho14bh7roi.cloudfront.net/app/uploads/2019/11/Strawbees-Nordic-Edtech-Award-1024x576.jpg",
				"link": "",
				"innerHTML": [
					{
						"tag": "text",
						"text": "Strawbees award 2019"
					}
				]
			},
			{
				"url": "https://d3lgho14bh7roi.cloudfront.net/app/uploads/2019/11/DSCF1525untitled-1024x1022.jpg",
				"link": "",
				"innerHTML": null
			}
		]
	},
	"innerBlocks": []
}
```
#### Group
```
{
	"name": "core/group",
	"data": {
		"variant": "rounded-corners",
		"sizeFormat": "normal",
		"anchor": "downloads",
		"backgroundColor": "#FF7381"
	},
	"innerBlocks": [...]
}
```
#### Heading
```
{
	"name": "core/heading",
	"data": {
		"variant": "card-header",
		"anchor": null,
		"horizontalAligment": "left",
		"level": 3,
		"textColor": null,
		"innerHTML": [
			{
				"tag": "text",
				"text": "Welcome!"
			}
		]
	},
	"innerBlocks": []
}
```
#### HTML
```
{
	"name": "core/html",
	"data": {
		"innerHTMLRaw": "\n<script>console.log('hey!')</script>\n"
	},
	"innerBlocks": []
}
```
#### Image
```
{
	"name": "core/image",
	"data": {
		"sizeFormat": "normal",
		"url": "https://d3lgho14bh7roi.cloudfront.net/app/uploads/2019/11/DSCF1525untitled-1024x1022.jpg",
		"link": "https://strawbees.com",
		"innerHTML": [
			{
				"tag": "text",
				"text": "This is the caption! With a "
			},
			{
				"tag": "a",
				"attributes": {
					"href": "https://strawbees.com"
				},
				"innerChildren": [{
					"tag": "text",
					"text": "link"
				}]
			},
			{
				"tag": "text",
				"text": "!"
			}
		]
	},
	"innerBlocks": []
}
```
#### List
```
{
	"name": "core/list",
	"data": {
		"innerHTML": {
			"tag": "ol",
			"attributes": {
				"reversed": true
			},
			"innerChildren": [
				{
					"tag": "li",
					"innerChildren": [
						{
							"tag": "text",
							"text": "First"
						}
					]
				},
				{
					"tag": "li",
					"innerChildren": [
						{
							"tag": "text",
							"text": "Second"
						}
					]
				},
				{
					"tag": "li",
					"innerChildren": [
						{
							"tag": "text",
							"text": "Third"
						},
						{
							"tag": "ul",
							"innerChildren": [
								{
									"tag": "li",
									"innerChildren": [
										{
											"tag": "text",
											"text": "Nested"
										}
									]
								}
							]
						}
					]
				}
			]
		}
	},
	"innerBlocks": []
}
```
#### Paragraph
```
{
	"name": "core/paragraph",
	"data": {
		"variant": null,
		"horizontalAligment": "left",
		"textColor": null,
		"backgroundColor": null,
		"innerHTML": [
			{
				"tag": "text",
				"text": "Some text!"
			}
		]
	},
	"innerBlocks": []
}
```
