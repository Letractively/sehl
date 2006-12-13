<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb">
 <head>
  <title>Highlighting search terms on your web site</title>
  <link href="article.css" media="screen,print" rel="stylesheet" type="text/css" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <style type="text/css">
   <!--
   span.filename{
    font-style:italic
   }
   span.hl1{
    background:rgb(153, 255, 255)
   }
   span.hl2{
    background:rgb(255, 153, 255)
   }
   span.hl3{
    background:rgb(255, 255, 153)
   }
   blockquote{
    background-color:#eee;
    border:1px dotted #ccc;
    margin:0 5em;
    padding:.5em 1em
   }
   -->
  </style>
 </head>
 <body>
  <div id="wrap">
   <h1>Highlighting search terms on your web site</h1>
   <div class="authors">By Brian Suda and Matt Riggott</div>
   <p>
    The last few years have seen <a href="http://www.google.com/" title="Google search engine">Google</a>
    rise to dominate the Internet search engine market at the expense of older
    and (at the time) more popular search engines. There are plenty of reasons
    why this happened, perhaps the most important being the continual
    functionality Google has added to its search tool. One such feature we find
    an essential is the cache it keeps of the sites it has crawled — almost all
    results that are returned from a query include a link to a copy of the page
    on Google’s servers. Viewing the cached page rather than viewing the
    original has some advantages; that Google’s server often returns the page
    faster and the original author need not know you’ve visited are just two.
    But by far the most useful is that the <span class="hl1">words</span> you
    <span class="hl2">searched</span> for are <span class="hl3">highlighted</span>
    in the page.
   </p>
   <p>
    Most web users don’t read pages, they scan text for what they’re looking
    for. (Ironically, everyone’s favourite usability expert, Jakob Nielsen, has
    written a long article about <a href="http://www.useit.com/papers/webwriting/writing.html"
    title="How to Write for the Web">how people don’t read long articles</a>.)
    There’s nothing you can do to change users’ habits, you can only help them
    find what they’re looking for. This is why Google’s cached-page highlighting
    is so useful. When the page is rendered users don’t need to read serially
    through the text, <em>the page shows them where it is</em>. As a quick
    example the words highlighted above most likely caught your eye before you
    actually got to reading them. <a href="http://www.useit.com/papers/heuristic/heuristic_list.html">Usability
    heuristics</a> state users should not have to remember information from one
    site to the next. Highlighting search terms can really change the way users
    read on the web.
   </p>
   <p>
    Google has this functionality available through its cache, but wouldn’t it
    be great if you could extend this feature to the pages on your own web site
    any time a visitor came from a search engine? How about highlighting search
    terms from your own site’s search tool? Not only would this be very cool it
    would be incredibly useful to the user.
   </p>
   <p>
    This would be a terrible article if we finished it here, an idea left
    hanging just waiting for somebody to come along and implement it. So we’re
    happy to say that we’ve implemented just such a system.

    We’ve written a script in <acronym title="PHP: Hypertext Preprocessor">PHP</acronym>
    that you can add to individual pages or entire web sites that will
    automatically highlight words in your page if the user has followed a link
    from a search engine results page. This article includes a overview of our
    implementation, instructions on adding the script to your site, and future
    work that could improve it. If you just want the script <a href="#download" title="SEHL download information">details are at the end of the article</a>.
   </p>
   <h2>Implementation</h2>
   <p>
    When someone visits your site from a search engine results page, that
    results page’s <acronym title="uniform resource locator">URL</acronym>
    is sent on to your site. This is known as the referring <acronym
    title="uniform resource locator">URL</acronym> or <em>referrer</em> (the
    <acronym title="Hypertext Transfer Protocol">HTTP</acronym> specification
    misspells this as ‘referer’), and can be accessed via scripting languages
    such as <a href="http://www.php.net/"><acronym title="PHP: Hypertext Preprocessor">PHP</acronym></a>,
    <a href="http://www.python.org/" title="Python, an interpreted, interactive, object-oriented, extensible programming language">Python</a>,
    and <a href="http://www.ecma-international.org/publications/standards/Ecma-262.htm"
    title="Standard ECMA-262: the ECMAScript scripting language specification">ECMAScript</a>
    (<abbr title="also known as">a.k.a</abbr> JavaScript). In that referrer
    there is a query string (assuming the search engine uses the <acronym
    title="Hypertext Tranfer Protocol">HTTP</acronym> ‘get’ method, something
    all the search engines we know do), which contains several keys and values.
    These look something like <code>search.php?q=<strong>SEARCH</strong>+<strong>TERMS</strong>+<strong>HERE</strong>&amp;l=en</code>.
    With these keys and values you can determine what terms were used on the
    search engine that listed your site as a result.
   </p>
   <p>
    The next step then, is to find all words in your page that match those that
    the user searched for on the search engine. Once you have a complete list of
    terms from the referrer’s query string, you surround each instance of a term with a <code>span</code> element that has a
    class something like <code>highlight</code>. Using your site’s cascading
    style sheet you then highlight these terms using background colours, font
    weights, different voices (depending on the target medium) so they are more
    apparent to the user. We give each search term a different class so they
    can be highlighted in different ways (e.g. every mention of ‘colour’ is
    highlighted in yellow, every mention of ‘coding’ is highlighted blue, and so
    on).
   </p>
   <p>
    This sounds fairly easy but there are complications that need to be
    considered. If the visitor searches for <samp>div</samp> you don’t want to
    replace all the <code>&lt;div&gt;</code> tags with <code>&lt;<strong>&lt;span
    class=&quot;highlight&quot;&gt;</strong>div<strong>&lt;/span&gt;</strong>&gt;</code>.
    You also don’t want to add <code>span</code> elements inside any attribute
    values, or you’ll end up with something like <code>&lt;img src=&quot;example.png&quot;
    alt=&quot;This is an example <b>&lt;span class=&quot;highlight&quot;&gt;</b>image<b>&lt;/span&gt;</b>&quot;/&gt;</code>
    which is obviously invalid <acronym title="Hypertext Markup Language">HTML</acronym>.
    To make sure we are only highlighting text outside of tags, we need to strip
    out the tags from the plain text, parse the plain text for search terms and
    wrap any instances in <code>span</code> tags, and finally put the plain text
    and the tags back together again — without changing the original structure
    or rendering of the page. We do this using <a href="http://foldoc.doc.ic.ac.uk/foldoc/foldoc.cgi?query=regular+expressions&amp;action=Search"
    title="Definition of regular expressions from FOLDOC">regular
    expressions</a>, a powerful tool that allows you to match patterns of text
    (see <acronym title="Comprehensive Perl Archive Network">CPAN</acronym> for
    a <a href="http://search.cpan.org/%7Enwclark/perl-5.8.3/pod/perlretut.pod">basic
    tutorial on using regular expressions</a>). If you want to find an <acronym
    title="Hypertext Markup Language">HTML</acronym> tag you could use <acronym
    title="PHP: Hypertext Preprocessor">PHP</acronym>’s string searching
    functions to find every possible combination of tags, but that takes a lot
    of work; with regular expressions you simply search for patterns. We use a
    pattern analogous to saying “look for ‘&lt;’ followed by any amount of
    characters that are not ‘&gt;’, followed by ‘&gt;’”. The <acronym
    title="Hypertext Markup Language">HTML</acronym> file acts as the input
    string the regular expression tries to match the pattern against. Using this
    we were able to separate the <acronym title="Hypertext Markup Language">HTML</acronym>
    tags and the plain text. We then take the untagged plain text and add the
    span tags around search terms, then put back the <acronym
    title="Hypertext Markup Language">HTML</acronym> tags in their original
    positions. This way any semantic meaning and presentation — visual, aural,
    or otherwise — is preserved, along with the structure and validity of
    markup.
   </p>
   <p>
    So far we have concentrated on static files, and you may be wondering how
    the highlighting functionality can be applied to dynamic pages, <abbr
    title="id est">i.e.</abbr> those that are not created in full until they are
    sent to the user-agent. This problem is solved with <acronym title="PHP: Hypertext Preprocessor">PHP</acronym>’s
    <a href="http://www.php.net/manual/en/ref.outcontrol.php">output buffering</a>.
    By calling a single function, <code><a href="http://www.php.net/manual/en/function.ob-start.php">ob_start</a></code>,
    at the top of your <acronym title="PHP: Hypertext Preprocessor">PHP</acronym>
    scripts, output is held in a buffer until you choose to output it to the
    <acronym title="Hypertext Transfer Protocol">HTTP</acronym> stream. The
    <code>ob_start</code> function takes the name of a function as its single
    argument. As the buffer is about to be output this function is called with
    the buffer’s contents passed as a parameter. Whatever the function returns
    is sent out into the ether to the user-agent. We can use this to modify the
    buffer by adding our highlighting <code>span</code> tags.
   </p>
   <p>
    Blimey. That’s enough techie-talk; time for a demonstration. We’ve rigged up
    an <a href="example.php">example search engine</a>:
    run a search, follow the result and the resulting page will highlight your
    search terms.
   </p>
   <h2>Adding it to your web site</h2>
   <p>
    Whether you run a large or small domain, new technology needs to be easily
    deployed and maintained. There are several ways to include the search engine
    highlighting function into your <acronym title="PHP: Hypertext Preprocessor">PHP</acronym>
    code. Here are just two.
   </p>
   <p>
    The first method all depends on how trusting your <abbr title="systems">sys.</abbr>
    <abbr title="administrator">admin.</abbr> is, but if you use the <a
    href="http://httpd.apache.org/">Apache web server</a> you may be able to add a
    <code>php_value auto_prepend_file</code> command to a <span class="filename">.htaccess</span>
    file. This asks Apache to add the contents of a file to the top of each page
    it serves. So to add the search engine highlighting functionality to your
    site you should add a line like:
   </p>
   <pre>php_value auto_prepend_file "/path/to/your/header.inc"</pre>
   <p>
    The <span class="filename">header.inc</span> file should contain the
    following code:
   </p>
   <pre>&lt;?php
  include "/absolute/path/to/sehl.php"; // File with search engine highlighting
  ob_start('sehl');
?&gt;</pre>
   <p>
    Notice that the <code>ob_start()</code> function takes one parameter, in
    this case a callback function, <code>sehl</code> (an abbreviation
    for ‘search engine highlight’). This is the function that will be called
    when the buffer is automatically flushed. The <acronym title="PHP: Hypertext Preprocessor">PHP</acronym>
    include statement includes <span class="filename">sehl.php</span>, which
    contains the <code>sehl</code> function. Once you’ve finished this minor
    fiddling you’re good to go. It’s important to note that Apache’s <span
    class="filename">.htaccess</span> file is a complex beastie, so if you want
    to know more you should read <a href="http://httpd.apache.org/docs/howto/htaccess.html"
    title="Tutorial from the Apache foundation on .htaccess files">Apache’s
    <span class="filename">.htaccess</span> file tutorial</a>.
   </p>
   <p>
    If you can’t use <span class="filename">.htaccess</span> files or you’re
    getting server errors, you won’t be able use <code>php_value auto_prepend_file</code>.
    That’s not a big problem because there is another method you can use to include the
    highlighting functionality. In each <acronym title="PHP: Hypertext Preprocessor">PHP</acronym>
    script you want to have search engine highlighting, simply add a line at the
    top of script that includes the <span class="filename">header.inc</span>
    file like so:
   </p>
   <pre>include('/path/to/your/header.inc')</pre>
   <h3>Notes on efficiencies</h3>
   <p>
    There are several points to be aware of before adding the search engine
    highlighting script to your site. Regular expressions are very complex and use lots of computer
    resources in attempting to match strings. The larger the body of text the
    more work the system has to do, and these can potentially effect performance.
    Small- to medium-sized sites should not have any need to worry, but large scale sites
    with millions of hits would need to evaluate the best possible way to
    implement this function. Output buffering requires a small overhead as
    well. The system has to hold your page in memory, edit it, then send a copy
    to the user. With lots and lots of requests this could also effect
    performance. In an attempt at optimisation, the <code>sehl</code> function
    will only execute a bare minimum of code if the referrer is not thought to
    be a search engine. No regular expressions will be be used and no words will
    be highlighted.
   </p>
   <h2>Future work</h2>
   <p>
    In its current state the <code>sehl</code> function will add a short
    explanation to the top of each page it highlights word in, like so:
   </p>
   <blockquote>
    <h4>Why are some words highlighted in this page?</h4>
    <p>
     This site’s search engine highlighting feature marks the words you just
     searched for easy identification.
    </p>
   </blockquote>
   <p>
    A nice extension to this would be to add links to each instance of the
    highlighted words as demonstrated below:
   </p>
   <blockquote>
    <p>
     You have just searched for <span class="hl1">search</span> <span
     class="hl2">terms</span> <span class="hl3">here</span>; there are 6
     instances on this page: <a href="#">1</a>, <a href="#">2</a>, <a href="#">3</a>,
     <a href="#">4</a>, <a href="#">5</a>, and <a href="#">6</a>.
    </p>
   </blockquote>
   <p>
    These numbered links would be anchors that jump through the page to the
    highlighted words. It would also be possible to integrate this into your own
    site’s search engine (<abbr title="exempli gratia">e.g.</abbr> <a href="http://www.atomz.com/">Atomz
    site search</a>). You already know the search terms the users are interested
    in, now you can pass those onto other services.
   </p>
   <blockquote>
    <p>
     You have just searched for <span class="hl1">search</span> <span
     class="hl2">terms</span> <span class="hl3">here</span>; there are 6
     instances on this page: <a href="#">1</a>, <a href="#">2</a>, <a href="#">3</a>,
     <a href="#">4</a>, <a href="#">5</a>, <a href="#">6</a>. This site’s own
     search engine has found <a href="#">34 instances</a> throughout the site.
    </p>
   </blockquote>
   <p>
    All this can help improve a user’s ability to find the information they are
    looking for; with this you can make your site an extension of the search
    engine. <a href="http://www.useit.com/alertbox/9710a.html">Users don’t read
    web sites, they scan them</a>, so anything that can help the user better
    access information is an improvement.
   </p>
   <p>
    The current implementation is clever enough to make sure it does not
    highlight partial matches, that is it will not highlight ‘day’ inside
    of ‘today’. It is also case-insensitive, so a search for ‘day’ will
    result in ‘Day’, ‘DAY’, <abbr title="et cetera">etc.</abbr> also being
    highlighted. These can both be easily changed to highlight partial
    matches and be case-sensitive respectively by making small changes to
    the regular expressions.
   </p>
   <p>
    Further implementations of this function would get extremely complex. When
    searching for a verb such as ‘list’ you could also highlight its
    derivatives: ‘lists’, ‘listing’, ‘listed’, and so on. The code can be
    customised to meet many of these needs, but the peculiarities of human
    languages mean it would be very difficult to provide a complete solution;
    therefore this is left to the interested user to make these changes if
    desired.
   </p>
   <h2 id="download">How to get the script</h2>
   <p>
    We anticipate this will be an ongoing project; you will always find the
    <a href="http://suda.co.uk/projects/SEHL/">latest version of the search
    engine highlight code</a> on Brian’s site. There are probably a million and one different
    ways that the code could be improved (we’ve already started on a fully
    object-oriented version ourselves), and any comments are welcome. We’ve
    released this code under the <a href="http://www.gnu.org/licenses/lgpl.html"><abbr title="GNU’s Not Unix">GNU</abbr>
    Lesser General Public Licence</a>,
    so you’re welcome to port the code to other scripting languages and do with
    it what you will. Enjoy!
   </p>
  </div>
 </body>
</html>