import Vue from 'vue'
import Vuex from 'vuex'
import f from './modules'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        categories: f('Categories'),
        course: f('Course'),
        courses: f('Courses'),
        coursesAddInfo: f('CoursesAddInfo'),
        block: f('Block'),
        lesson: f('Lesson'),
        user: f('User'),
        users: f('Users'),
        roles: f('Roles'),
        tags: f('Tags'),
        settings: f('Settings'),
        themes: f('Themes'),
        directories: f('Directories'),
        taskCategories: f('TaskCategories')
    }
})