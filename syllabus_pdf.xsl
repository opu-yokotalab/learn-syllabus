<?xml version="1.0" encoding="EUC-JP" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                version="1.0">

  <xsl:output method="text" encoding="EUC-JP"/>
  <!-- ����Х�XML��ʸ��Ρ��ɤ��Ф���Tex�����Ȥߤ���Ϥ��� -->
  <xsl:template match="syllabus">

\documentclass[a4j,10pt]{jarticle}
\makeatletter
\def\Hline{%
\noalign{\ifnum0=`}\fi\hrule \@height 1pt \futurelet
\reserved@a\@xhline}
\makeatother
\usepackage{array}

\setlength{\textheight}{280mm}
\setlength{\textwidth}{172mm}
\setlength{\oddsidemargin}{-6.4mm}
\setlength{\evensidemargin}{-6.4mm}
\setlength{\voffset}{-22mm}
%\setlength{\hoffset}{-2mm}

\pagestyle{empty}

% ���顼��̵��
%\batchmode

\begin{document}
%\begin{center}
%\begin{picture}(0,0)
%    \put(0,-745){<xsl:apply-templates select="page"/>\hspace{2zw}}
%\end{picture}
%\end{center}
\begin{center}
\begin{small}
{\renewcommand{\arraystretch}{1.5}
\begin{tabular}{ccccccccc}


% �ڼ��Ȳ���̾�� ������̾ �ҵ����̾�� (��ʸ����̾ �ҵ��ʸ����̾��)

<!-- 1�ʤˤ����硧Unicodeʸ������Ƚ�� -->
<xsl:if test="(string-length(subject_name/name_ja) +
 (string-length(subject_name/name_en) div 2)) &lt;= 35">

\multicolumn{1}{l}{\parbox[c]{7zw}{
{\textbf{\normalsize �ڼ��Ȳ���̾��}}
}} <xsl:text disable-output-escaping="yes">&amp;</xsl:text> <xsl:text>
 </xsl:text>

\multicolumn{8}{l}{\parbox[c]{<xsl:value-of select="33-(string-length(subject_name/name_en) div 2)"/>zw}{
{\textbf {\normalsize <xsl:value-of select="subject_name/name_ja"/>}}
}
\parbox[r]{<xsl:value-of select="(string-length(subject_name/name_en) div 2) + 9"/>zw}{
{\textbf {\normalsize <xsl:apply-templates select="subject_name/name_en"/>}}
}}
\vspace{1mm}\\ \hline
</xsl:if>

<!-- 2�ʤˤ�����1 -->
<xsl:if test="((string-length(subject_name/name_ja) + (string-length(subject_name/name_en) div 2)) &gt; 35) and (string-length(subject_name/name_en) &lt;= 70)">
\multicolumn{1}{l}{\parbox[c]{7zw}{
{\textbf{\normalsize �ڼ��Ȳ���̾��}}
}} <xsl:text disable-output-escaping="yes">&amp;</xsl:text> <xsl:text> </xsl:text>
\multicolumn{8}{l}{
{\textbf {\vspace{-2mm}\normalsize <xsl:value-of select="subject_name/name_ja"/>}}
}
\\
\multicolumn{1}{l}{
{\textbf{\normalsize \hspace{8zw}}}
} <xsl:text disable-output-escaping="yes">&amp;</xsl:text> <xsl:text> </xsl:text>
\multicolumn{8}{r}{
{\textbf {\normalsize \hspace{<xsl:value-of select="30-(string-length(subject_name/name_en) div 2)"/>zw}<xsl:apply-templates select="subject_name/name_en"/>}}
}
\vspace{1mm}\\ \hline
</xsl:if>

<!-- 2�ʤˤ��Ƥ��̾��Ĺ����� -->
<xsl:if test="((string-length(subject_name/name_ja) + (string-length(subject_name/name_en) div 2)) &gt; 35) and (string-length(subject_name/name_en) &gt; 70)">
\multicolumn{1}{l}{\parbox[c]{7zw}{
{\textbf{\normalsize �ڼ��Ȳ���̾��}}
}} <xsl:text disable-output-escaping="yes">&amp;</xsl:text> <xsl:text> </xsl:text>
\multicolumn{8}{l}{
{\textbf {\vspace{-2mm}\normalsize <xsl:value-of select="subject_name/name_ja"/>}}
}
\\
\multicolumn{1}{l}{
{\textbf{\normalsize \hspace{8zw}}}
} <xsl:text disable-output-escaping="yes">&amp;</xsl:text> <xsl:text> </xsl:text>
\multicolumn{8}{r}{
{\textbf {\footnotesize \hspace{<xsl:value-of select="30-(string-length(subject_name/name_en) div 2)"/>zw}<xsl:apply-templates select="subject_name/name_en"/>}}
}
\vspace{1mm}\\ \hline
</xsl:if>

% ô������
\multicolumn{1}{l}{\parbox[t]{8zw}{\textbf{��ô��������}
}} <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
 <xsl:text> </xsl:text>
\multicolumn{8}{l}{\parbox[t]{38zw}{
<xsl:apply-templates select="teachers"/>
}}
\\

% �оݳ���
\multicolumn{1}{l}{\parbox[t]{6zw}{\textbf{���оݳ�����}
}} <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
 <xsl:text> </xsl:text>
\multicolumn{3}{l}{\parbox[t]{12zw}{<xsl:value-of select="student"/>
}} <xsl:text disable-output-escaping="yes">&amp;</xsl:text> 
<xsl:text> </xsl:text>

%���ȷ���
\multicolumn{1}{l}{\parbox[t]{6zw}{\textbf{�ڼ��ȷ��֡�}
}} <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
 <xsl:text> </xsl:text>
\multicolumn{4}{l}{\parbox[t]{12zw}{<xsl:apply-templates
 select="subject_form"/>
}}
\\

%ɬ�����������
\multicolumn{1}{l}{\parbox[t]{8zw}{\textbf{��ɬ����������̡�}
}} <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
 <xsl:text> </xsl:text>
\multicolumn{3}{l}{\parbox[t]{12zw}{<xsl:value-of select="choice"/>
}} <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
 <xsl:text> </xsl:text>

%ñ�̿�
\multicolumn{1}{l}{\parbox[t]{6zw}{\textbf{��ñ\hspace{1mm}��\hspace{1mm}����}
}} <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
 <xsl:text> </xsl:text>
\multicolumn{4}{l}{\parbox[t]{12zw}{<xsl:value-of select="credit"/>ñ��
}}
\vspace{1mm}\\ \hline


% ��ά
\multicolumn{2}{l}{\parbox[t]{9zw}{\textbf{�ڳ�\hspace{6mm}ά��}
}} \\
\multicolumn{9}{l}{
\hspace{-2mm}\begin{minipage}{50zw}
<xsl:apply-templates select="outline"/>
\end{minipage}
}
\\

% ���Ȳ��ܤ���ã��ɸ
\multicolumn{2}{l}{\parbox[t]{11zw}{\textbf{�ڼ��Ȳ��ܤ���ã��ɸ��}
}} \\
\multicolumn{9}{l}{
\hspace{-2mm}\begin{minipage}{50zw}
<xsl:apply-templates select="object"/>
\end{minipage}
}
\\

% ����������
\multicolumn{2}{l}{\parbox[t]{8zw}{\textbf{�����������ա�}
}} \\
\multicolumn{9}{l}{
\hspace{-2mm}\begin{minipage}{50zw}
\begin{tabular}{l@{ : }l}
�������׷� <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
 <xsl:text> </xsl:text>
\parbox[t]{42zw}{
<xsl:apply-templates select="attention/necessary"/>
} \\
����¾ <xsl:text disable-output-escaping="yes">&amp;</xsl:text>
 <xsl:text> </xsl:text>
\parbox[t]{42zw}{
<xsl:apply-templates select="attention/others"/>
}
\end{tabular}
\end{minipage}
}
\\

% �������Ƥȥ������塼��
\multicolumn{3}{l}{\parbox[t]{13zw}{\textbf{�ڼ������Ƥȥ������塼���}
}} \vspace{2mm}\\
\multicolumn{9}{l}{
\hspace{-3mm}
<xsl:apply-templates select="subject_plan"/>
\end{minipage}
} 
\vspace{2mm}\\ \hline

% ����ɾ��
\multicolumn{2}{l}{\parbox[t]{6zw}{\vspace{-2mm}\textbf{\vspace{2mm}������ɾ����}
}} \\
\multicolumn{9}{l}{
\begin{minipage}{50zw}
<xsl:apply-templates select="granding"/>
\end{minipage}
}
\\

%��Ϣ���Ȳ���
\multicolumn{2}{l}{\parbox[t]{8zw}{\vspace{-2mm}\textbf{�ڴ�Ϣ���Ȳ��ܡ�}
}} \\
\multicolumn{9}{l}{\parbox[t]{50zw}{ <xsl:apply-templates select="relation"/>
}}
\\

% ����
\multicolumn{2}{l}{\parbox[t]{8zw}{\vspace{-1mm}\textbf{�ڶ�\hspace{6mm}���}
}} \\
\multicolumn{9}{l}{
\hspace{-1mm}\begin{minipage}{50zw}
\begin{tabular}{l@{ }l}
���ʽ�\ :\ 
<xsl:apply-templates select="text/text_books"/>
���ͽ�\ :\ 
<xsl:apply-templates select="text/ref_books"/>
\end{tabular}
\end{minipage}
} 
\\

% ����
\multicolumn{2}{l}{\parbox[t]{8zw}{\vspace{-1mm}\textbf{����\hspace{6mm}�͡�}
}} \\
\multicolumn{9}{l}{
\vspace{-4mm}\begin{minipage}{50zw}
<xsl:apply-templates select="remark"/>
\end{minipage}
}

\end{tabular}
}
\end{small}
\end{center}
%\newpage

\end{document}

  </xsl:template>

   <xsl:template match="subject_name/name_en">
      <xsl:if test="not(.='')">
        <xsl:text>��</xsl:text><xsl:value-of select="."/><xsl:text>��</xsl:text>
      </xsl:if>
  </xsl:template>

   <xsl:template match="teachers">
    <xsl:for-each select="teacher">
      <xsl:apply-templates/>
	<xsl:choose>
	 <xsl:when test="./@em_form='part'">(����)
	 </xsl:when>
	 <xsl:when test="./@em_form='full'">
	 </xsl:when>
	</xsl:choose>
      <xsl:if test="not(.='') and not(position()=last())">
        <xsl:text>\hspace{4mm}�����ֹ�(</xsl:text><xsl:value-of select="/syllabus/contact/room_num"/><xsl:text>),�Żҥ᡼��(</xsl:text><xsl:value-of select="/syllabus/contact/e_mail"/><xsl:text>)\vspace{1mm}\\</xsl:text>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>
 
  <xsl:template match="teacher">
    <xsl:value-of select="."/>
  </xsl:template>

  <xsl:template match="subject_form">
    <xsl:choose>
	  <xsl:when test=".='�ֵ��µ�'">
	    <xsl:text>\scriptsize{</xsl:text><xsl:value-of select="."/>
	    <xsl:text>}</xsl:text>
	  </xsl:when>
	  <xsl:otherwise>
	    <xsl:value-of select="."/>
	  </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template match="outline">
    <xsl:for-each select="p">
      <xsl:apply-templates/>
      <xsl:if test="not(position()=last())">
        <xsl:text>\hspace{1sp} \\
</xsl:text>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>


  <xsl:template match="object">
    <xsl:for-each select="p">
      <xsl:apply-templates/>
      <xsl:if test="not(position()=last())">
        <xsl:text>\hspace{1sp} \\
</xsl:text>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>


  <xsl:template match="necessary">
    <xsl:for-each select="p">
      <xsl:apply-templates/>
      <xsl:if test="not(position()=last())">
        <xsl:text>\hspace{1sp} \\
</xsl:text>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>


  <xsl:template match="others">
    <xsl:for-each select="p">
      <xsl:apply-templates/>
      <xsl:if test="not(position()=last())">
        <xsl:text>\hspace{1sp} \\
</xsl:text>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>


  <xsl:template match="subject_plan">
    <xsl:choose>
      <xsl:when test="./@form='list'">
\hspace{-3mm}\begin{minipage}{50zw}
\begin{enumerate}
        <xsl:for-each select="li">
\item <xsl:for-each select="p">
            <xsl:apply-templates/>
            <xsl:if test="not(position()=last())">
              <xsl:text>\hspace{1sp} \\
</xsl:text>
            </xsl:if>
          </xsl:for-each>
        </xsl:for-each>
\end{enumerate}
      </xsl:when>
      <xsl:when test="./@form='free'">
\begin{minipage}{50zw}
<xsl:for-each select="p">
          <xsl:apply-templates/>
          <xsl:if test="not(position()=last())">
            <xsl:text>\hspace{1sp} \\
</xsl:text>
          </xsl:if>
        </xsl:for-each>
      </xsl:when>
    </xsl:choose>
  </xsl:template>

  <xsl:template match="li">
\item <xsl:for-each select="p">
        <xsl:apply-templates/>
        <xsl:if test="not(position()=last())">
          <xsl:text>\hspace{1sp} \\
</xsl:text>
        </xsl:if>
      </xsl:for-each>
  </xsl:template>


  <xsl:template match="granding">
    <xsl:for-each select="p">
      <xsl:apply-templates/>
      <xsl:if test="not(position()=last())">
        <xsl:text>\hspace{1sp} \\
</xsl:text>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>


  <xsl:template match="relation">
    <xsl:for-each select="p">
      <xsl:apply-templates/>
      <xsl:if test="not(position()=last())">
        <xsl:text>\hspace{1sp} \\
</xsl:text>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>


  <xsl:template match="text_books">
    <xsl:for-each select="book">
      <xsl:text> &amp; \parbox[t]{44zw}{</xsl:text>
      <xsl:apply-templates/>
      <xsl:text>}</xsl:text>
          <xsl:text>\hspace{1sp}\\
</xsl:text>
    </xsl:for-each>
  </xsl:template>


  <xsl:template match="ref_books">
    <xsl:for-each select="book">
      <xsl:if test="string-length() != 0">
        <xsl:text> &amp; \parbox[t]{44zw}{</xsl:text>
        <xsl:apply-templates/>
        <xsl:text>}</xsl:text>
        <xsl:text>\hspace{1sp}\\
</xsl:text>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>


  <xsl:template match="book">
    <xsl:value-of select="."/>
  </xsl:template>


  <xsl:template match="remark">
    <xsl:for-each select="p">
      <xsl:apply-templates/>
      <xsl:if test="not(position()=last())">
        <xsl:text>\hspace{1sp} \\
</xsl:text>
      </xsl:if>
    </xsl:for-each>
  </xsl:template>


  <xsl:template match="p">
    <xsl:value-of select="."/>
  </xsl:template>


</xsl:stylesheet>  
