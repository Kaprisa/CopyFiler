import 'markdown-it-icons/dist/index.css'
import 'markdown-it-highlight/dist/index.css'
//import 'markdown-it-mermaid/dist/i'


const md = require('markdown-it')({
    html: true,
    linkify: true,
    typographer: true,
}).set({ breaks: true })

import mk from '@iktakahiro/markdown-it-katex'
const { markdownItTable } = require('markdown-it-table')
import mct from 'markdown-it-center-text'
import mc from 'markdown-it-container'
import markdownitIcons from 'markdown-it-icons'
import markdownItHighlight from 'markdown-it-highlight'
import markdownItMark from 'markdown-it-mark'
import markdownItIns from 'markdown-it-ins'
import markdownItDeflist from 'markdown-it-deflist'
import markdownItAbbr from 'markdown-it-abbr'
import markdownItFootnote from 'markdown-it-footnote'
import markdownItGithubToc from 'markdown-it-github-toc'
import markdownItTaskList from 'markdown-it-task-list'
import markdownItSourceMap from 'markdown-it-source-map'
import markdownItChart from 'markdown-it-chart'
import markdownItMermaid from 'markdown-it-mermaid'
import Chart from 'chart.js'
import Cookies from 'js-cookie'

md.use(mk)
md.use(mct)
md.use(markdownItTable)
md.use(markdownitIcons, 'font-awesome')
md.use(markdownItHighlight)
md.use(markdownItMark)
md.use(markdownItIns)
md.use(markdownItDeflist)
md.use(markdownItAbbr)
md.use(markdownItFootnote)
md.use(markdownItTaskList)
md.use(markdownItSourceMap)
md.use(markdownItChart)
md.use(markdownItMermaid)
md.use(markdownItGithubToc, {
    tocFirstLevel: 2,
    tocLastLevel: 3,
    tocClassName: 'toc',
    anchorLinkSymbol: '',
    anchorLinkSpace: false,
    anchorClassName: 'anchor',
    anchorLinkSymbolClassName: 'octicon octicon-link'
})
md.use(mc, 'spoiler', {

    validate: function(params) {
        return params.trim().match(/^spoiler\s+(.*)$/);
    },

    render: function (tokens, idx) {
        var m = tokens[idx].info.trim().match(/^spoiler\s+(.*)$/);

        if (tokens[idx].nesting === 1) {
            // opening tag
            return '<details><summary>' + md.utils.escapeHtml(m[1]) + '</summary>\n';

        } else {
            // closing tag
            return '</details>\n';
        }
    }
})

md.mermaid.loadPreferences(Cookies)


export default md