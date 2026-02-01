'use client';

import { CheckCircle, XCircle, AlertCircle, TrendingUp, Award, BookOpen, Lightbulb, AlertTriangle } from 'lucide-react';

export type Language = 'en' | 'id';

interface ScreeningResultsProps {
    result: any | null;
    language: Language;
}

export default function ScreeningResults({ result, language }: ScreeningResultsProps) {
    if (!result) {
        return (
            <div className="flex flex-col items-center justify-center h-64 text-center">
                <div className="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                    <TrendingUp className="w-8 h-8 text-gray-400" />
                </div>
                <p className="text-gray-500 dark:text-gray-400">
                    {language === 'en' ? 'Upload a resume to see results' : 'Unggah resume untuk melihat hasil'}
                </p>
            </div>
        );
    }

    const getRecommendationColor = () => {
        switch (result.recommendation) {
            case 'Highly Recommended':
                return 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800';
            case 'Recommended':
                return 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800';
            case 'Maybe':
                return 'text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800';
            default:
                return 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800';
        }
    };

    const getScoreColor = (score: number) => {
        if (score >= 80) return 'from-green-600 to-green-400';
        if (score >= 60) return 'from-blue-900 to-blue-500';
        if (score >= 40) return 'from-yellow-600 to-yellow-400';
        return 'from-red-600 to-red-400';
    };

    return (
        <div className="space-y-6 max-h-[600px] overflow-y-auto pr-2">
            {/* Overall Score */}
            <div className="text-center p-6 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 rounded-xl">
                <div className={`text-6xl font-bold bg-gradient-to-r ${getScoreColor(result.overallScore)} bg-clip-text text-transparent mb-2`}>
                    {result.overallScore}
                </div>
                <p className="text-sm text-gray-600 dark:text-gray-400">Overall Score</p>
            </div>

            {/* Recommendation */}
            <div className={`p-4 rounded-lg border ${getRecommendationColor()}`}>
                <p className="font-semibold text-center">{result.recommendation}</p>
            </div>

            {/* Summary */}
            {result.summary && (
                <div className="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                    <p className="text-sm text-gray-700 dark:text-gray-300">{result.summary}</p>
                </div>
            )}

            {/* Matched Skills */}
            {result.matchedSkills && result.matchedSkills.length > 0 && (
                <div className="space-y-2">
                    <div className="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                        <CheckCircle className="w-4 h-4 text-green-500" />
                        <span className="text-sm">Matched Skills</span>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        {result.matchedSkills.map((skill: string, index: number) => (
                            <span
                                key={index}
                                className="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-sm border border-green-200 dark:border-green-700"
                            >
                                {skill}
                            </span>
                        ))}
                    </div>
                </div>
            )}

            {/* Missing Skills */}
            {result.missingSkills && result.missingSkills.length > 0 && (
                <div className="space-y-2">
                    <div className="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                        <XCircle className="w-4 h-4 text-red-500" />
                        <span className="text-sm">Missing Skills</span>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        {result.missingSkills.map((skill: string, index: number) => (
                            <span
                                key={index}
                                className="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full text-sm border border-red-200 dark:border-red-700"
                            >
                                {skill}
                            </span>
                        ))}
                    </div>
                </div>
            )}

            {/* Experience */}
            {result.experience && (
                <div className="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div className="flex items-center gap-2 mb-2">
                        <Award className="w-4 h-4 text-blue-500" />
                        <span className="text-sm font-medium text-gray-700 dark:text-gray-300">Experience</span>
                    </div>
                    <p className="text-sm text-gray-600 dark:text-gray-400">
                        {result.experience.years} years • Relevance: {result.experience.relevance}
                    </p>
                </div>
            )}

            {/* Education */}
            {result.education && (
                <div className="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div className="flex items-center gap-2 mb-2">
                        <BookOpen className="w-4 h-4 text-blue-500" />
                        <span className="text-sm font-medium text-gray-700 dark:text-gray-300">Education</span>
                    </div>
                    <p className="text-sm text-gray-600 dark:text-gray-400">
                        {result.education.level} • Relevance: {result.education.relevance}
                    </p>
                </div>
            )}

            {/* Strengths */}
            {result.strengths && result.strengths.length > 0 && (
                <div className="space-y-2">
                    <div className="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                        <Lightbulb className="w-4 h-4 text-blue-500" />
                        <span className="text-sm">Strengths</span>
                    </div>
                    <ul className="space-y-1">
                        {result.strengths.map((strength: string, index: number) => (
                            <li key={index} className="text-sm text-gray-600 dark:text-gray-400 pl-4">
                                • {strength}
                            </li>
                        ))}
                    </ul>
                </div>
            )}

            {/* Concerns */}
            {result.concerns && result.concerns.length > 0 && (
                <div className="space-y-2">
                    <div className="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-medium">
                        <AlertTriangle className="w-4 h-4 text-yellow-500" />
                        <span className="text-sm">Concerns</span>
                    </div>
                    <ul className="space-y-1">
                        {result.concerns.map((concern: string, index: number) => (
                            <li key={index} className="text-sm text-gray-600 dark:text-gray-400 pl-4">
                                • {concern}
                            </li>
                        ))}
                    </ul>
                </div>
            )}
        </div>
    );
}
